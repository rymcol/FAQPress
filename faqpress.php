<?php
/*
Plugin Name: FAQPress
Plugin URI: https://github.com/rymcol/FAQPress
Description: Creates an FAQ System in Wordpress. Shortcode usage: <code>[faqpress]</code>
Version: 1.0
Author: Ryan Collins
Author URI: http://ryanmcollins.com/
License: https://raw.githubusercontent.com/rymcol/FAQPress/master/LICENSE
*/
 
// Custom Post Type & Taxonomy Registration
require_once('includes/cpt.php');
 
// Create das shortcodes
require_once('includes/shortcodes.php');

//Add Style
function load_faqpress_css() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'FAQPress', $plugin_url . 'faqpress.css' );
}
add_action( 'wp_enqueue_scripts', 'load_fqpress_css' );

// Handle Activation and Deactivation
function faqpress_activate() {
    faqpress_cpt();
    flush_rewrite_rules();
}
 
register_activation_hook( __FILE__, 'faqpress_activate' );
 
function faqpress_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'faqpress_deactivate' );
 
?>