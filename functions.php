<?php
/**
 * Theme functions.
 *
 * @package Twenty_Seventeen_Westonson
 */

define( 'TWENTYSEVENTEEN_WESTONSON_DEFAULT_FOOTER_SITE_INFO', sprintf(
	'<a href="%s">%s</a>',
	esc_url( __( 'https://wordpress.org/', 'twentyseventeen' ) ),
	/* translators: placeholder is WordPress */
	sprintf( __( 'Proudly powered by %s', 'twentyseventeen' ), 'WordPress' )
) );

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