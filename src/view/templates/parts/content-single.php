<?php
/**
 * Template part for displaying posts
 */

?>

<style>
	#gcal a{
		color: #FFFF;
	}
	.event-container{
		display:block;
	}
	.event-container div {
		display:inline;
	}
	.event-container #map {
		float: right;
	}
	.event-container #content {
		float: left;
	}

</style>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >

	<header class="entry-header">
		<?php include_once( SEL_ROOT . '/view/templates/parts/entry-header.php' ); ?>
	</header>

	<div class="entry-content event-container">
		<div id="content">
			<p>
				<?php include_once( SEL_ROOT . '/view/templates/parts/event-date-time-section.php' ); ?>
			</p>

			<p>
				<?php include_once( SEL_ROOT . '/view/templates/parts/google-calendar-button.php' ); ?>
			</p>

			<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentynineteen' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'twentynineteen' ),
					'after'  => '</div>',
				)
			);
			?>

		</div>


		<div id="map">
			<?php
				include_once( SEL_ROOT . '/view/templates/parts/google-maps-section.php' );
			?>
		</div>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author', 'bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
