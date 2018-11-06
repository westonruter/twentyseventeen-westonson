<?php
/**
 * The template for displaying offline pages
 *
 * @link https://github.com/xwp/pwa-wp
 *
 * @package Twenty_Seventeen_Westonson
 */

// Prevent showing nav menus.
add_filter( 'has_nav_menu', '__return_false' );

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-offline">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! It looks like you&#8217;re offline.', 'twentyseventeen-westonson' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<?php
					if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) {
						wp_service_worker_error_message_placeholder();
					}
					?>
				</div><!-- .page-content -->
			</section><!-- .error-offline -->
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
