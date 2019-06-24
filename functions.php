<?php
/**
 * Theme functions.
 *
 * @package Twenty_Seventeen_Westonson
 */

add_action(
	'after_setup_theme',
	function() {
		// Set the default content width.
		$GLOBALS['content_width'] = 1000;
	},
	11
);

add_filter(
	'twentyseventeen_content_width',
	function( $content_width ) {
		$page_layout = get_theme_mod( 'page_layout' );

		$is_single_column = (
			( 'one-column' === $page_layout && ( twentyseventeen_is_frontpage() || is_page() ) )
			||
			( is_single() && ! is_active_sidebar( 'sidebar-1' ) )
		);
		if ( $is_single_column ) {
			$content_width = 1348;
		}

		return $content_width;
	}
);

/*
 * To enable service worker streaming (which also forces native mode), use WP-CLI as follows:
 *
 *     wp theme mod set service_worker_navigation streaming
 *
 * To enable AMP comments live list <https://github.com/Automattic/amp-wp/wiki/Commenting#live-commenting-using-a-live-list>, do:
 *
 *     wp theme mod set amp_comments_live_list true
 */
add_action(
	'after_setup_theme',
	function() {

		$amp_comments_live_list = rest_sanitize_boolean( get_theme_mod( 'amp_comments_live_list', false ) );
		$has_streaming          = 'streaming' === get_theme_mod( 'service_worker_navigation' );

		// Abort if classic mode.
		if ( 'classic' === $amp_mode ) {
			return;
		}

		$support_args = array(
			'paired' => true,
		);

		$support_args['service_worker'] = array(
			'cdn_script_caching'   => true,
			'google_fonts_caching' => true,
			'image_caching'        => true,
		);

		if ( $amp_comments_live_list ) {
			$support_args['comments_live_list'] = true;
		}

		if ( $has_streaming ) {
			add_theme_support( 'service_worker_streaming' );
		}

		add_theme_support( 'amp', $support_args );
	}
);

/*
 * Remove novalidate attribute from comment form to force client-side form validation to prevent relying on server-side
 * validation which will not be available during offline commenting. This ensures that background sync wont POST a
 * comment that we already know to be invalid.
 */
add_filter(
	'amp_content_sanitizers',
	function( $sanitizers ) {
		require_once __DIR__ . '/class-comment-form-yesvalidate-sanitizer.php';
		$sanitizers['Twenty_Seventeen_Westonson\Comment_Form_YesValidate_Sanitizer'] = array();
		return $sanitizers;
	}
);

// Make sure the precached streaming header varies by the header logo and header image.
add_filter(
	'wp_streaming_header_precache_entry',
	function( $entry ) {
		if ( isset( $entry['revision'] ) ) {
			$entry['revision'] .= md5(
				wp_json_encode(
					// Put everything here that can cause the streaming header to vary.
					array(
						'v1',
						home_url( '/' ),
						get_bloginfo( 'name' ),
						get_bloginfo( 'description' ),
						get_custom_header(),
						get_theme_mod( 'custom_logo' ),
						file_get_contents( locate_template( array( 'template-parts/header/header-image.php' ) ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
						file_get_contents( locate_template( array( 'template-parts/header/site-branding.php' ) ) ), // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
					)
				)
			);
		}
		return $entry;
	}
);


add_filter( 'widget_text_content', 'wp_make_content_images_responsive' );

define(
	'TWENTYSEVENTEEN_WESTONSON_DEFAULT_FOOTER_SITE_INFO',
	sprintf(
		'<a href="%s">%s</a>',
		esc_url( __( 'https://wordpress.org/', 'twentyseventeen' ) ),
		/* translators: placeholder is WordPress */
		sprintf( __( 'Proudly powered by %s', 'twentyseventeen' ), 'WordPress' )
	)
);

/**
 * Print image preload link for attachment.
 *
 * @param string|int|WP_Post $image Image URL or attachment.
 * @param string|array|null  $size  Image size.
 */
function twentyseventeen_westonson_print_preload_image_link( $image, $size = null ) {
	$attachment = null;
	$image_meta = null;
	if ( is_string( $image ) ) {
		$src = $image;
	} else {
		$attachment = get_post( $image );
		if ( ! $attachment ) {
			return;
		}

		$image_src = wp_get_attachment_image_src( $attachment->ID, $size );
		if ( ! $image_src ) {
			return;
		}

		list( $src, $width, $height ) = $image_src;

		$size = array( absint( $width ), absint( $height ) );

		$image_meta = wp_get_attachment_metadata( $attachment->ID );
	}

	printf( '<link rel="preload" as="image" href="%s"', esc_url( $src ) );

	if ( $attachment && is_array( $image_meta ) && is_array( $size ) ) {
		$srcset = wp_calculate_image_srcset( $size, $src, $image_meta, $attachment->ID );
		if ( $srcset ) {
			printf( ' imgsrcset="%s"', esc_attr( $srcset ) );
		}
		$sizes = wp_calculate_image_sizes( $size, $src, $image_meta, $attachment->ID );
		if ( $sizes ) {
			printf( ' imgsizes="%s"', esc_attr( $sizes ) );
		}
	}
	echo ">\n";
}

// Preload stuff.
add_action(
	'wp_head',
	function() {
		global $wp_query;

		// Since link[imgsizes] and link[imgsrcset] aren't supported yet, preloading images can actually waste bandwidth by requesting a URL that isn't used.
		if ( ! isset( $_GET['try_image_preloads'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		// Preload Custom Logo.
		if ( current_theme_supports( 'custom-logo' ) && get_theme_mod( 'custom_logo' ) ) {
			twentyseventeen_westonson_print_preload_image_link( (int) get_theme_mod( 'custom_logo' ), 'full' );
		}

		// Preload Featured Image.
		if ( isset( $wp_query->posts[0] ) && has_post_thumbnail( $wp_query->posts[0] ) ) {
			twentyseventeen_westonson_print_preload_image_link( (int) get_post_thumbnail_id( $wp_query->posts[0] ), 'post-thumbnail' );
		}

		// Preload Custom Header.
		if ( current_theme_supports( 'custom-header' ) && get_custom_header() && get_custom_header()->url ) {
			$custom_header = get_custom_header();
			twentyseventeen_westonson_print_preload_image_link( $custom_header->attachment_id ? $custom_header->attachment_id : $custom_header->url, array( $custom_header->width, $custom_header->height ) );
		}

		// Preload Custom Background.
		if ( current_theme_supports( 'custom-background' ) && get_background_image() ) {
			twentyseventeen_westonson_print_preload_image_link( get_background_image() );
		}
	},
	1
);

// Make parent theme's stylesheet a dependency for this theme's stylesheet.
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_register_style(
			'twentyseventeen-parent-style',
			trailingslashit( get_template_directory_uri() ) . 'style.css',
			array(),
			'1.1'
		);
		wp_styles()->registered['twentyseventeen-style']->deps[] = 'twentyseventeen-parent-style';
	},
	20
);

add_action(
	'customize_register',
	function( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_setting(
			'footer_site_info',
			array(
				'type'              => 'option',
				'transport'         => 'postMessage',
				'default'           => TWENTYSEVENTEEN_WESTONSON_DEFAULT_FOOTER_SITE_INFO,
				'sanitize_callback' => function ( $value ) {
					return wp_kses_post( $value );
				},
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Code_Editor_Control(
				$wp_customize,
				'footer_site_info',
				array(
					'label'     => 'Footer',
					'section'   => 'title_tagline',
					'code_type' => 'text/html',
					'settings'  => array( 'footer_site_info' ),
					'priority'  => 45,
				)
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'footer_site_info',
			array(
				'settings'            => array( 'footer_site_info' ),
				'selector'            => '.site-info',
				'container_inclusive' => true,
				'render_callback'     => function () {
					\get_template_part( 'template-parts/footer/site', 'info' );
				},
			)
		);
	}
);

// Remove the has-sidebar class from the 500.php and offline.php templates since they do not have the sidebar.
add_filter(
	'body_class',
	function( $body_classes ) {
		if ( is_404() || ( function_exists( 'is_500' ) && is_500() ) || ( function_exists( 'is_offline' ) && is_offline() ) ) {
			$body_classes = array_diff( $body_classes, array( 'has-sidebar' ) );
		}
		return $body_classes;
	},
	11
);

// Mark scripts and styles which will be precached. Any dependencies of these scripts will be automatically precached.
add_action(
	'wp_enqueue_scripts',
	function() {
		$precached_styles = array(
			'wp-block-library', // From Gutenberg.
			'twentyseventeen-parent-style',
			'twentyseventeen-style',
		);
		foreach ( $precached_styles as $handle ) {
			wp_style_add_data( $handle, 'precache', true );
		}

		$precached_scripts = array(
			'twentyseventeen-skip-link-focus-fix',
			'twentyseventeen-navigation',
			'twentyseventeen-global',
		);
		foreach ( $precached_scripts as $handle ) {
			wp_script_add_data( $handle, 'precache', true );
		}
	},
	PHP_INT_MAX
);

// Add offline template to list of templates in AMP.
add_filter(
	'amp_supportable_templates',
	function( $supportable_templates ) {
		if ( function_exists( 'is_offline' ) ) {
			$supportable_templates['is_offline'] = array(
				'label' => __( 'Offline', 'twentyseventeen-westonson' ),
			);
		}
		return $supportable_templates;
	}
);

/*
 * As alternative to precaching scripts for offline page, just use the AMP version instead.
 * This has benefit of automatically excluding other scripts enqueued by plugins.
 *
 * To enable this, set the theme mod flag via WP-CLI:
 *
 *     wp theme mod set amp_offline_page true
 */
if ( function_exists( 'is_amp_endpoint' ) ) {
	add_filter(
		'wp_offline_error_precache_entry',
		function( $entry ) {
			if ( ! rest_sanitize_boolean( get_theme_mod( 'amp_offline_page', false ) ) ) {
				return $entry;
			}

			$supportable_templates = AMP_Theme_Support::get_supportable_templates();
			if ( ! amp_is_canonical() && ! empty( $supportable_templates['is_offline']['supported'] ) && is_array( $entry ) ) {
				$entry['url'] = add_query_arg( amp_get_slug(), '', $entry['url'] );
			}
			return $entry;
		}
	);
}
