<?php
/**
 * Plugin Name
 *
 * @package           Xtremelocator
 * @author            Linas Adomavicius
 * @copyright         2021 XtremeLocator
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Xtremelocator
 * Plugin URI:        http://www.xtremelocator.com
 * Description:       This plugin allows you to deploy the Xtreme Locator dealer locator  application on your Wordpress website.  Complete help files are available at the Xtreme Locator  website.
 * Version:           3.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Linas Adomavicius
 * Author URI:        http://www.xtremelocator.com
 * Text Domain:       plugin-slug
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*Init variables*/
require_once __DIR__ . '/config.php';


if ( ! session_id() ) {
	session_start();
	session_write_close(); // Other plugins can restart a session again via session_start()
}

/*Init variables end*/

add_action( 'admin_init', 'editor_admin_init' );
add_action( 'admin_head', 'editor_admin_head' );

function editor_admin_init() {
	wp_enqueue_script( 'word-count' );
	wp_enqueue_script( 'post' );
	wp_enqueue_script( 'editor' );
	wp_enqueue_script( 'media-upload' );
}

function editor_admin_head() {
	//wp_tiny_mce();
}

//add_action('admin_print_scripts', 'xl_add_admin_javascript');
add_action( 'admin_print_styles', 'xl_add_admin_stylesheet' );
add_action( 'wp_print_styles', 'xl_add_public_stylesheet' );

register_activation_hook( __FILE__, 'xl_install' );
register_uninstall_hook( __FILE__, 'xl_uninstall' );

//add_filter( 'the_content', 'includeLocator', 7 );

add_shortcode( 'xtreme_locator_standard', 'shortcode_handler_function_standard' );
add_shortcode( 'xtreme_locator_advanced', 'shortcode_handler_function_advanced' );
add_shortcode( 'xtreme_locator_custom', 'shortcode_handler_function_custom' );
add_shortcode( 'xtreme_locator_all', 'shortcode_handler_function_all' );
add_shortcode( 'xtreme_locator_listing', 'shortcode_handler_function_listing' );
//add_filter( 'content_save_pre', 'checkLocatorTag', 7 );

add_action( 'admin_menu', 'xl_init_admin_menus' );

add_filter( 'option_update_plugins', 'xl_prevent_upgrade' );
add_filter( 'transient_update_plugins', 'xl_prevent_upgrade' );

load_plugin_textdomain( 'text_domain', false, "/wp-content/plugins/" . XL_DIR . "/languages/" );

//add_action( "plugins_loaded", "xl_register_widgets" );


add_action( 'admin_post_nopriv_xtreme_locator', 'xtremelocator_form_parser' );

?>
