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
	<img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Headers/home-hero.jpg" alt="Today, marketing moves at the speed of light. We're Red/E to help you keep up." />
</div>

<div class="front-cta row">
	<div class="small-12 columns">
		<p>Get Red/E for something completely different: a totally automated, quick serve marketing platform that lets you easily create and implement marketing programs fast.</p>
		<p>Get started today with FREE sign-up.</p>
	</div>
</div>

<div class="front-clients full-width">
	<img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Headers/clients.png" alt="Our Clients" />
</div>

<div class="front-cta2 row">
	<div class="small-12 medium-6 columns">
		<img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Headers/computer.png">
	</div>
	<div class="small-12 medium-6 columns">
        <h3 class="font-open-sans rede text-center font-size-26">Get Red/E for fast, streamlined, and affordable way to create customized marketing programs.</h3>
        <p class="font-size-24 text-center">
            Fragmented, time-consuming program development is a thing of the past. Red/E quick serve marketing's digital platform lets you rapidily and effortlessly create marketing programs. Plus you can manage all aspects of delivery from any digital service.
        </p>
    </div>
</div>

<div class="front-clients full-width">
	<img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Headers/home-banner-2.png" alt="AutoMarketing: The Game Changer" />
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
