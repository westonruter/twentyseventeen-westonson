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
					<details id="error-details" hidden>
						<summary><?php esc_html_e( 'More details', 'twentyseventeen-westonson' ); ?></summary>
						<iframe srcdoc=""></iframe>
						<script>
						function renderErrorDetails( data ) {
							if ( data.bodyText.trim().length ) {
								const details = document.getElementById( 'error-details' );
								details.querySelector( 'iframe' ).srcdoc = data.bodyText;
								details.hidden = false;
							}
						}
						</script>
						<?php wp_print_service_worker_error_details_script( 'renderErrorDetails' ); ?>
					</details>
				</div><!-- .page-content -->
			</section><!-- .error-500 -->
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
get_footer();
