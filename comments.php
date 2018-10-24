<?php
/**
 * The template for displaying comments in AMP.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @todo apply changes
 *
 * @package Twenty_Seventeen_Westonson
 */

$amp_theme_support = get_theme_support( 'amp' );

$supports_comments_live_list = (
	function_exists( 'is_amp_endpoint' )
	&&
	is_amp_endpoint()
	&&
	! empty( $amp_theme_support[0]['comments_live_list'] )
);

if ( ! $supports_comments_live_list ) {
	require get_template_directory() . '/' . basename( __FILE__ );
	return;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

// @todo When there are no comments, the amp-live-list should be populated with something to indicate that that there are no comments yet.
// @todo When the first comment is posted, the CSS will be broken because it will have been tree-shaken. We should prevent tree-shaking for any comment CSS when on a post that has commenting enabled.
?>

<div id="comments" class="comments-area">

	<h2 class="comments-title">
		<?php esc_html_e( 'Comments', 'twentyseventeen-westonson' ); ?>
	</h2>

	<amp-live-list id="amp-live-comments-list-<?php the_ID(); ?>" data-max-items-per-page="10000"
		<?php
		if ( 'desc' !== get_option( 'comment_order' ) ) {
			echo 'sort="ascending"';
		};
		?>
	>
		<ol items class="comment-list">
			<?php
			wp_list_comments( array(
				'avatar_size' => 100,
				'style'       => 'ol',
				'short_ping'  => true,
				'reply_text'  => twentyseventeen_get_svg( array( 'icon' => 'mail-reply' ) ) . __( 'Reply', 'twentyseventeen' ),
			) );
			?>
		</ol>
		<div update class="live-list__button">
			<button class="button" on="tap:amp-live-comments-list-<?php the_ID(); ?>.update">
				<?php esc_html_e( 'New comment(s)', 'ampconf' ); ?>
			</button>
		</div>
		<nav pagination>
			<?php
			the_comments_pagination( array(
				'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous', 'twentyseventeen' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
			) );
			?>
		</nav>
	</amp-live-list>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyseventeen' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</div><!-- #comments -->
