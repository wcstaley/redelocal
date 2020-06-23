<?php
/*
  Plugin Name: Red/E Core
  Description: Adds custom functionality to the Red/E Website
  Version: 1.0
  Author: AMP Agency (jbishop@ampagency.com)
  Author URI: http://www.ampagency.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if (!defined('REDE_PLUGIN_URL')) {
    define('REDE_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('REDE_PLUGIN_PATH')) {
    define('REDE_PLUGIN_PATH', plugin_dir_path(__FILE__));
}
if (!defined('REDE_PLUGIN_BASENAME')) {
    define('REDE_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
if (!defined('REDE_PLUGIN_ADMIN')) {
    define('REDE_PLUGIN_ADMIN', get_bloginfo('url') . "/wp-admin");
}
if (!defined('REDE_CORE')) {
    define('REDE_CORE', "1.0");
}

if (!defined('EXCEL_UPLOADS')) {
  define('EXCEL_UPLOADS', dirname(__FILE__) . '/../../uploads/csv');
}

register_activation_hook( __FILE__, 'rede_activate' );

add_action( 'init', 'rede_init', 9999 );

function rede_activate() {
	require_once(REDE_PLUGIN_PATH . "inc/activate.php");
}
function rede_init() {
  
}	


// Modify WordPress
require_once(REDE_PLUGIN_PATH . "inc/disable-pingback.php");

// Pull in files
require_once(REDE_PLUGIN_PATH . "inc/constants.php");
require_once(REDE_PLUGIN_PATH . "inc/order-data.php");
require_once(REDE_PLUGIN_PATH . "inc/helper.php");
require_once(REDE_PLUGIN_PATH . "inc/stores.php");
require_once(REDE_PLUGIN_PATH . "inc/excel.php");
require_once(REDE_PLUGIN_PATH . "inc/users.php");
require_once(REDE_PLUGIN_PATH . "inc/email.php");
require_once(REDE_PLUGIN_PATH . "inc/pageflex.php");
require_once(REDE_PLUGIN_PATH . "inc/forms.php");
require_once(REDE_PLUGIN_PATH . "inc/wp-overrides.php");
require_once(REDE_PLUGIN_PATH . "inc/post-types.php");
require_once(REDE_PLUGIN_PATH . "inc/shortcode.php");
require_once(REDE_PLUGIN_PATH . "inc/ajax.php");

// require_once(REDE_PLUGIN_PATH . "inc/site-options.php");
// require_once(REDE_PLUGIN_PATH . "inc/meta.php");
// require_once(REDE_PLUGIN_PATH . "inc/forms.php");
// require_once(REDE_PLUGIN_PATH . "inc/payment.php");
// require_once(REDE_PLUGIN_PATH . "inc/profiles.php");
// require_once(REDE_PLUGIN_PATH . "inc/shortcodes.php");
// require_once(REDE_PLUGIN_PATH . "inc/widgets.php");
// require_once(REDE_PLUGIN_PATH . "inc/functions.php");
