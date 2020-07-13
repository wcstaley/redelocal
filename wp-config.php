<?php
# Database Configuration
define( 'DB_NAME', 'wp_publixmarketin' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );
define( 'DB_HOST', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

define('WP_HOME', 'http://'.$_SERVER['HTTP_HOST']);
define('WP_SITEURL', 'http://'.$_SERVER['HTTP_HOST']);

# Security Salts, Keys, Etc
define('AUTH_KEY',         '_!uN:mQj0=mC^+QE1vF8N}x^u7DH.V>S-k)@I&$%FA5+=^?1{WNS.BbWHoHhnm_+');
define('SECURE_AUTH_KEY',  '+Lhq&sr]wNoynk[NW*q_[Xd#H#?GK=M6B6t2N=@:?v&^.F-ohJCrg^^6fD3.UY`-');
define('LOGGED_IN_KEY',    'TnAT+|97J#(IrdZ2v4,1U5%P}+:S7$^CJFp;^N(6i|ytr0RB`PqY|baz_Im>ku_&');
define('NONCE_KEY',        'pX)E3Y}^$g#Fp1,q*c<0)s#( }].+q{zM|C=A9{lS+X-o:KnXmb|hkxPV>u;V-q;');
define('AUTH_SALT',        'Mfu}m6ieIacOrkL|c7,E{`.9;kuN:fP-}$oPh$I:,@s+,7 l9v--^NZxQ{N%?],2');
define('SECURE_AUTH_SALT', '8#Ok4,a<7-s?Jnm3;c>XqkXszGr$LUU8xi&-Zi=NF,0I+kt= vXS{-p= v>+E3*!');
define('LOGGED_IN_SALT',   '[OH|cBO]sA/j>yU}Vj&5wF+ejcdOb_L|F>P$BXqB+Vr_|uC]BoY@ov=oF$99BWwU');
define('NONCE_SALT',       '3Zd-x|GoOet@ L<:Iu@*5:Ho YpJwQ+D4Gbjna9mN1LN%#,hZI1eMVH34>L+EI$+');


define( 'WP_DEBUG', TRUE );
define( 'WP_DEBUG_DISPLAY', TRUE );
define( 'WP_DEBUG_LOG', true );

//until site is live, remove the error handler
define('WP_DISABLE_FATAL_ERROR_HANDLER',true);

# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'publixmarketin' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '993fc83f5059f3dd1a02ff5f02189481720e831a' );

define( 'WPE_CLUSTER_ID', '100537' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', true );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'publixmarketin.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-100537', );

$wpe_special_ips=array ( 0 => '104.196.192.123', );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');
