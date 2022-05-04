<?php do_action( 'fl_before_post' ); ?>
<article <?php post_class( 'fl-post' ); ?> id="fl-post-<?php the_ID(); ?>">

	<?php do_action( 'fl_before_post_content' ); ?>
	<div class="fl-post-content clearfix" itemprop="text">
		<?php
			the_content();

			wp_link_pages( array(
				'before'         => '<div class="fl-post-page-nav">' . _x( 'Pages:', 'Text before page links on paginated post.', 'fl-automator' ),
				'after'          => '</div>',
				'next_or_number' => 'number',
			) );
			?>
	</div><!-- .fl-post-content -->
	<?php do_action( 'fl_after_post_content' ); ?>

</article>
<?php do_action( 'fl_after_post' ); ?>
<!-- .fl-post -->
