<?php
/**
 * The template for displaying 500 pages (internal server errors)
 *
 * @link https://github.com/xwp/pwa-wp
 *
 * @package Twenty_Seventeen_Westonson
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-500 internal-server-error">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'Oops! Something went wrong.', 'twentyseventeen-westonson' ); ?></h1>
				</header><!-- .page-header -->
				<div class="page-content">
					<p><?php esc_html_e( 'Something prevented the page from being rendered. Please try again.', 'twentyseventeen-westonson' ); ?></p>

					<?php
					if ( function_exists( 'wp_service_worker_error_details_template' ) ) {
						wp_service_worker_error_details_template();
					}
					?>
			</section><!-- .error-500 -->
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
