<?php
/**
 * The header with app shell awareness.
 *
 * @package Twenty_Seventeen_Westonson
 */


require get_template_directory() . '/header.php';

if ( function_exists( 'amp_start_app_shell_content' ) ) {
	amp_start_app_shell_content();
}
