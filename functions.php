<?php
/**
 * Theme functions.
 *
 * @package Twenty_Seventeen_Westonson
 */

add_action( 'after_setup_theme', function() {
	add_theme_support( 'amp', array(
		'paired' => true,
	) );
} );

add_filter( 'widget_text_content', 'wp_make_content_images_responsive' );

define( 'TWENTYSEVENTEEN_WESTONSON_DEFAULT_FOOTER_SITE_INFO', sprintf(
	'<a href="%s">%s</a>',
	esc_url( __( 'https://wordpress.org/', 'twentyseventeen' ) ),
	/* translators: placeholder is WordPress */
	sprintf( __( 'Proudly powered by %s', 'twentyseventeen' ), 'WordPress' )
) );

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
add_action( 'wp_head', function() {
	global $wp_query;

	// Since link[imgsizes] and link[imgsrcset] arent supported yet, preloading images can actually waste bandwidth by requesting a URL that isn't used.
	if ( ! isset( $_GET['try_image_preloads'] ) ) {
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
}, 1 );

// Make parent theme's stylesheet a dependency for this theme's stylesheet.
add_action( 'wp_enqueue_scripts', function() {
	wp_register_style(
		'twentyseventeen-parent-style',
		trailingslashit( get_template_directory_uri() ) . 'style.css',
		array(),
		false
	);
	wp_styles()->registered['twentyseventeen-style']->deps[] = 'twentyseventeen-parent-style';
}, 20 );

add_action( 'customize_register', function( WP_Customize_Manager $wp_customize ) {
	$wp_customize->add_setting( 'footer_site_info', array(
		'type'              => 'option',
		'transport'         => 'postMessage',
		'default'           => TWENTYSEVENTEEN_WESTONSON_DEFAULT_FOOTER_SITE_INFO,
		'sanitize_callback' => function ( $value ) {
			return wp_kses_post( $value );
		},
	) );

	$wp_customize->add_control( new WP_Customize_Code_Editor_Control( $wp_customize, 'footer_site_info', array(
		'label'     => 'Footer',
		'section'   => 'title_tagline',
		'code_type' => 'text/html',
		'settings'  => array( 'footer_site_info' ),
		'priority'  => 45,
	) ) );

	$wp_customize->selective_refresh->add_partial( 'footer_site_info', array(
		'settings'            => array( 'footer_site_info' ),
		'selector'            => '.site-info',
		'container_inclusive' => true,
		'render_callback'     => function () {
			\get_template_part( 'template-parts/footer/site', 'info' );
		},
	) );
} );

// Make it easier to test changes to the offline.php template by bumping the precache entry revision by the modified time.
add_filter( 'wp_offline_error_precache_entry', function( $entry ) {
	if ( WP_DEBUG && is_array( $entry ) ) {
		$entry['revision'] .= '-' . filemtime( __DIR__ . '/offline.php' );
	}
	return $entry;
} );

// Make it easier to test changes to the 500.php template by bumping the precache entry revision by the modified time.
add_filter( 'wp_server_error_precache_entry', function( $entry ) {
	if ( WP_DEBUG && is_array( $entry ) ) {
		$entry['revision'] .= '-' . filemtime( __DIR__ . '/500.php' );
	}
	return $entry;
} );

// Remove the has-sidebar class from the 500.php and offline.php templates since they do not have the sidebar.
add_filter( 'body_class', function( $body_classes ) {
	if ( ( function_exists( 'is_500' ) && is_500() ) || ( function_exists( 'is_offline' ) && is_offline() ) ) {
		$body_classes = array_diff( $body_classes, array( 'has-sidebar' ) );
	}
	return $body_classes;
}, 11 );

/**
 * Get style handles that should be pre-cached.
 *
 * @return array Style handles.
 */
function twentyseventeen_get_precached_styles() {
	return array(
		'wp-block-library', // From Gutenberg.
		'twentyseventeen-parent-style',
		'twentyseventeen-style',
	);
}

/**
 * Get script handles that are pre-cached.
 *
 * @return array Script handles.
 */
function twentyseventeen_get_precached_scripts() {
	return array(
		'jquery-core',
		'jquery-migrate',
		'twentyseventeen-skip-link-focus-fix',
		'twentyseventeen-navigation',
		'twentyseventeen-global',
		'jquery-scrollto',
	);
}

// Dequeue all enqueued scripts from the offline page, except for those which are precached.
add_filter( 'print_scripts_array', function( $handles ) {
	$is_offline = ( function_exists( 'is_offline' ) && is_offline() );
	if ( $is_offline ) {
		$handles = array_intersect( twentyseventeen_get_precached_scripts(), $handles );
	}
	return $handles;
} );

// Dequeue all enqueued styles from the offline page, except for those which are precached.
add_filter( 'print_styles_array', function( $handles ) {
	$is_offline = ( function_exists( 'is_offline' ) && is_offline() );
	if ( $is_offline ) {
		$handles = array_intersect(
			array_merge(
				twentyseventeen_get_precached_styles(),
				array( 'twentyseventeen-fonts' ) // Not pre-cached but runtime-cached.
			),
			$handles
		);
	}
	return $handles;
} );

// Precache scripts, styles, custom logo, and custom header; do runtime caching of fonts.
add_action( 'wp_front_service_worker', function( WP_Service_Workers $service_workers ) {
	$service_workers->register_precached_site_icon();
	$service_workers->register_precached_custom_logo();
	$service_workers->register_precached_custom_header();
	$service_workers->register_precached_scripts( twentyseventeen_get_precached_scripts() );
	$service_workers->register_precached_styles( twentyseventeen_get_precached_styles() );

	// Use cache-first runtime caching for Google Fonts. Ported from <https://gist.github.com/sebastianbenz/1d449dee039202d8b7464f1131eae449>.
	$service_workers->register_cached_route(
		'https://fonts.(?:googleapis|gstatic).com/(.*)',
		WP_Service_Workers::STRATEGY_CACHE_FIRST,
		array(
			'cacheName' => 'twentyseventeen-westonson-fonts',
			'plugins'   => array(
				'cacheableResponse' => array(
					'statuses' => array( 0, 200 ),
				),
				'expiration'        => array(
					'maxEntries' => 30,
				),
			),
		)
	);
} );
