<?php
# Database Configuration
define ( 'ACF_PRO_KEY', 'b3JkZXJfaWQ9NjA4NTZ8dHlwZT1wZXJzb25hbHxkYXRlPTIwMTUtMDctMjkgMTI6MTc6NTE=' );
define( 'WP_DEBUG', false );
define( 'DB_NAME', 'wp_bevidev' );
define( 'DB_USER', 'bevidev' );
define( 'DB_PASSWORD', '__ezyNWOWcu1d05Gl2xm' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '>*||~^xJ9us3 1t#5#CTQ-(*|M8X]E7Y7Zp[_T(-Ff2Avh|*F:34.a5RDeC35n-X');
define('SECURE_AUTH_KEY',  'p{xp+vP^zQFaE}zW@0o:M1_e)Y `C~lJ;}XiKjGB5i_5oDAVa[6yH8Dk`aMlWz+G');
define('LOGGED_IN_KEY',    'pG#jJnE}hp6Vl#p`=C$Jz:W`LM@bqr<`O?:_$B=1&m)v.XA?~lm=xLy)TH2G3;1^');
define('NONCE_KEY',        '%I!f^@8a{-+07S4w(aOL(JGYu8L&?4hYPIb`DYbn-h.xKvtNu}G-YJJm[ EjOFS/');
define('AUTH_SALT',        '=tj:(NmOT_+2}zpThvB7#oFT@J7O39g~V oV@opng*< VX19I}5!Qi)w-FU}?0m@');
define('SECURE_AUTH_SALT', 'DbtZSK1zqmcD~:Al+2_1I[K*k%J[uqd<xXubTO/_&<2u$^uG(YN=Rp rM|;6:0b-');
define('LOGGED_IN_SALT',   'b]zi$yLHdRbJvK7}W@hdf#pj^1d1g;m+WkPs]<-+wTJ#^*r.-Qo;taT,~(,67[#0');
define('NONCE_SALT',       '_C3lA]lATUUn;}PD]20k(&qnKafp.}NhT.!iW>>QP41C:+ir .|J3bV*+~8&Q:3-');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'bevidev' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'WPE_APIKEY', '636f9223afee0851be5c6597fbaa98cbf9f7a83f' );

define( 'WPE_CLUSTER_ID', '156465' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

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

$wpe_all_domains=array ( 0 => 'bevidev.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-156465', );

$wpe_special_ips=array ( 0 => '34.72.228.69', );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( );

define( 'WPE_SFTP_ENDPOINT', '' );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings






# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');
