<?php

if ( ! defined( 'WP_CLI' ) || 'cli' !== php_sapi_name() ) {
	fwrite( STDERR, "Must be run via WP-CLI.\n" );
	exit( 1 );
}

global $wp_customize;
require_once ABSPATH . '/wp-includes/class-wp-customize-manager.php';

$wp_customize = new WP_Customize_Manager( array(
	'settings_previewed' => false,
) );
do_action( 'customize_register', $wp_customize );

$wp_customize->import_theme_starter_content();
$r = $wp_customize->save_changeset_post( array(
	'title' => 'Configured theme with starter content',
	'status' => 'publish',
) );


if ( is_wp_error( $r ) ) {
	WP_CLI::error( $r->get_error_code() );
} else {
	WP_CLI::success( 'Changeset published: ' . $wp_customize->changeset_uuid() );
}
