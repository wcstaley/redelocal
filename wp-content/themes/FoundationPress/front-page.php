<?php
/**
 * The front page template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<div class="front-hero full-width">
	<img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Headers/home-hero.jpg" alt="Today, marketing moves at the speed of light. Publix Marketing can help you keep up." />
</div>

<div class="front-grid">
	<main class="main-content">
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				

			<?php endwhile; ?>

		<?php endif; // End have_posts() check. ?>
	</main>

</div>

<?php get_footer();
