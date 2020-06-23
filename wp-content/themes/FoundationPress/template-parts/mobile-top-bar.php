<?php
/**
 * Template part for mobile top bar menu
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>

<nav class="mobile-menu vertical menu" id="<?php foundationpress_mobile_menu_id(); ?>" role="navigation">

	<?php
		if(!is_user_logged_in()){
			foundationpress_mobile_nav(); 
		} else {
			foundationpress_mobile_loggedin();
		}
	?>
</nav>
