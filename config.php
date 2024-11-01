<?php


define( 'XL_DIR', dirname( plugin_basename( __FILE__ ) ) );
define( 'XL_BASE', get_option( 'siteurl' ) . "/wp-content/plugins/" . XL_DIR );
define( 'XL_PATH', ABSPATH . "wp-content/plugins/" . XL_DIR );
define( 'XL_TEXT_DOMAIN', 'XL' );
define( 'XL_VERSION', '3.0.1' );
define( 'XTREME_LOCATOR_SALT', 'etuikk.5t6hu465rtherth546jkrynery435y' );
define( 'ADMIN_POST_URL', 	 admin_url() .'admin-post.php' );


require_once __DIR__ . '/functions.xtremelocator.php';

