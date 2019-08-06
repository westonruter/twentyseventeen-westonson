<?php
/**
 * Template part for displaying posts with excerpts
 *
 * Used in Search Results and for Recent Posts in Front Page panels.
 *
 * @package Twenty_Seventeen_Westonson
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'content-excerpt' ); ?>>

	<header class="entry-header">

		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" class="featured-image-thumbnail">
				<?php
				$add_fixed_layout = function( $attributes ) {
					$attributes['data-amp-layout'] = 'fixed';
					return $attributes;
				};
				$add_media_min_width_1100px = function( $attributes ) {
					$attributes['media'] = '(min-width: 1100px)';
					return $attributes;
				};
				$add_media_max_width_1100px = function( $attributes ) {
					$attributes['media'] = '(max-width: 1099px)';
					return $attributes;
				};

				add_filter( 'wp_get_attachment_image_attributes', $add_fixed_layout );
				add_filter( 'wp_get_attachment_image_attributes', $add_media_min_width_1100px );
				the_post_thumbnail( 'thumbnail' );
				remove_filter( 'wp_get_attachment_image_attributes', $add_media_min_width_1100px );
				remove_filter( 'wp_get_attachment_image_attributes', $add_fixed_layout );

				add_filter( 'wp_get_attachment_image_attributes', $add_media_max_width_1100px );
				the_post_thumbnail( 'post-thumbnail' );
				remove_filter( 'wp_get_attachment_image_attributes', $add_media_max_width_1100px );
				?>
			</a>
		<?php endif; ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php
				echo twentyseventeen_time_link();
				twentyseventeen_edit_link();
				?>
			</div><!-- .entry-meta -->
		<?php elseif ( 'page' === get_post_type() && get_edit_post_link() ) : ?>
			<div class="entry-meta">
				<?php twentyseventeen_edit_link(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php if ( is_front_page() && ! is_home() ) {

			// The excerpt is being displayed within a front page section, so it's a lower hierarchy than h2.
			the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
		} else {
			the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		} ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
