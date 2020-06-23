<?php
/**
 * The template for displaying orders
 *
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<?php get_template_part( 'template-parts/featured-image' ); ?>
<div class="main-container">
	<div class="main-grid">
		<main class="main-content-full-width">
			<?php
			while ( have_posts() ) :
				the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php 				
						$post_id = get_the_ID();
						$order_data = get_all_meta($post_id);
						foreach($order_data as $order_name=>$order_val){
							echo '<strong>' . $order_name . '</strong>: ' . $order_val . '<br>';
						} 
						?>
					</div>
				</article>
			<?php endwhile; ?>
		</main>
	</div>
</div>
<?php
get_footer();
