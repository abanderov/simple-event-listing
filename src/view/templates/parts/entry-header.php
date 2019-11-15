<?php
/**
 * Template part for displaying the header of the post
 *
 */

the_title( '<h1 class="entry-title">', '</h1>' );

?>

<?php if ( ! is_page() ) : ?>
<div class="entry-meta">
	<?php
	// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'twentynineteen' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	?>
</div><!-- .entry-meta -->
<?php endif; ?>
