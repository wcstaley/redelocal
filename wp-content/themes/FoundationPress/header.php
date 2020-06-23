<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?> >
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

	<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) === 'offcanvas' ) : ?>
		<?php get_template_part( 'template-parts/mobile-off-canvas' ); ?>
	<?php endif; ?>

	<header class="site-header" role="banner">
		<div class="small-12 columns row">
			<div class="site-title-bar title-bar" <?php foundationpress_title_bar_responsive_toggle(); ?>>
				<div class="title-bar-left">
					<a href="<?php echo (is_user_logged_in() ? esc_url( home_url( '/dashboard' ) ) : esc_url( home_url( '/' ) ) ); ?>" rel="home">
						<img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Logo/Publix-Logo.png" alt="Red/E" />
						<span><?php bloginfo( 'name' ); ?></span>
					</a>
					<button aria-label="<?php _e( 'Main Menu', 'foundationpress' ); ?>" class="menu-icon" type="button" data-toggle="<?php foundationpress_mobile_menu_id(); ?>"></button>
				</div>
			</div>

			<nav class="site-navigation top-bar" role="navigation">
				<div class="top-bar-left">
					<div class="site-desktop-title top-bar-title">
						<a href="<?php echo (is_user_logged_in() ? esc_url( home_url( '/dashboard' ) ) : esc_url( home_url( '/' ) ) ); ?>" rel="home">
						<img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/Logo/rede_100.jpg" alt="Red/E" />
						<?php bloginfo( 'name' ); ?></a>
					</div>
				</div>
				<div class="top-bar-right">
					<?php

					if(!is_user_logged_in()){
						foundationpress_top_bar_r(); 
					} else {
						foundationpress_top_bar_r_loggedin();
					}
					?>

					<?php if ( ! get_theme_mod( 'wpt_mobile_menu_layout' ) || get_theme_mod( 'wpt_mobile_menu_layout' ) === 'topbar' ) : ?>
						<?php get_template_part( 'template-parts/mobile-top-bar' ); ?>
					<?php endif; ?>
				</div>
			</nav>
		</div>
	</header>

	<?php if(is_singular() && has_post_thumbnail()){ 
		$feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() );
		?>
		<div class="front-hero full-width">
			<img src="<?php echo $feat_image_url; ?>" />
			<?php if(is_singular()){ 
				$post_id = get_the_ID();
				$head_title = get_the_title($post_id);
				$head_text = get_post_meta($post_id, '_head_text', true);
				if(!empty($head_text)){ ?>
					<div id="head_text">
						<h5><?php echo $head_title; ?></h5>
						<p><?php echo $head_text; ?></p>
					</div>
				<?php }
			} ?>
		</div>
	<?php } ?>