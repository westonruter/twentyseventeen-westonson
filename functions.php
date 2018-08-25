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

	$is_amp_endpoint = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint(); // Because imgsrcset and imgsizes aren't allowed yet (and they aren't even implemented yet either for that matter).
	if ( $attachment && is_array( $image_meta ) && is_array( $size ) && ! $is_amp_endpoint ) {
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
