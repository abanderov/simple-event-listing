<?php
/**
 * Template Name: Single Event Page
*/
get_header();
?>

<section id="primary" class="content-area">
	<main id="main" class="site-main">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
			?>
		</header><!-- .page-header -->

		<?php

		// Start the Loop.
		global $query_string;

		query_posts( array( // order the archive according to event date (new to old)
		'post_type' => 'sel-event',
		'posts_per_page' => 4,
		'meta_key' => '_sel_start_date',
		'orderby' => '_sel_start_date',
		'order' => 'DESC',
		    'meta_query' => array(
		        array(
		           'key' => '_sel_start_date',
		       )
			)
		));

		while ( have_posts() ) :
			the_post();

			include( SEL_ROOT . '/view/templates/parts/content-excerpt.php' );

			// End the loop.
		endwhile;

		// Previous/next page navigation.
		posts_nav_link();

		// If no content, include the "No posts found" template.
	else :
		include( SEL_ROOT . '/view/templates/parts/content-none.php' );

	endif;
	?>
	</main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();
