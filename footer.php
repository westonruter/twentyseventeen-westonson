<?php
/**
 * The footer with app shell awareness.
 *
 * @package Twenty_Seventeen_Westonson
 */

if ( function_exists( 'amp_end_app_shell_content' ) ) {
	amp_end_app_shell_content();
}

require get_template_directory() . '/footer.php';
