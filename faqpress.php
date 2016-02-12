<?php
/*
Plugin Name: FAQPress
Plugin URI: https://github.com/rymcol/FAQPress
Description: Creates an FAQ System in Wordpress. Shortcode usage: <code>[faq]</code>
Version: 1.0
Author: Ryan Collins
Author URI: http://ryanmcollins.com/
License: https://raw.githubusercontent.com/rymcol/FAQPress/master/LICENSE
*/
 
if ( ! function_exists( 'tuts_faq_cpt' ) ) {
 
// register custom post type
    function tuts_faq_cpt() {
 
        // these are the labels in the admin interface, edit them as you like
        $labels = array(
            'name'                => _x( 'FAQs', 'Post Type General Name', 'tuts_faq' ),
            'singular_name'       => _x( 'FAQ', 'Post Type Singular Name', 'tuts_faq' ),
            'menu_name'           => __( 'FAQ', 'tuts_faq' ),
            'parent_item_colon'   => __( 'Parent Item:', 'tuts_faq' ),
            'all_items'           => __( 'All Items', 'tuts_faq' ),
            'view_item'           => __( 'View Item', 'tuts_faq' ),
            'add_new_item'        => __( 'Add New FAQ Item', 'tuts_faq' ),
            'add_new'             => __( 'Add New', 'tuts_faq' ),
            'edit_item'           => __( 'Edit Item', 'tuts_faq' ),
            'update_item'         => __( 'Update Item', 'tuts_faq' ),
            'search_items'        => __( 'Search Item', 'tuts_faq' ),
            'not_found'           => __( 'Not found', 'tuts_faq' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'tuts_faq' ),
        );
        $args = array(
            // use the labels above
            'labels'              => $labels,
            // we'll only need the title, the Visual editor and the excerpt fields for our post type
            'supports'            => array( 'title', 'editor', 'excerpt', ),
            // we're going to create this taxonomy in the next section, but we need to link our post type to it now
            'taxonomies'          => array( 'tuts_faq_tax' ),
            // make it public so we can see it in the admin panel and show it in the front-end
            'public'              => true,
            // show the menu item under the Pages item
            'menu_position'       => 20,
            // show archives, if you don't need the shortcode
            'has_archive'         => true,
        );
        register_post_type( 'tuts_faq', $args );
 
    }
 
    // hook into the 'init' action
    add_action( 'init', 'tuts_faq_cpt', 0 );
 
}
 
if ( ! function_exists( 'tuts_faq_tax' ) ) {
 
    // register custom taxonomy
    function tuts_faq_tax() {
 
        // again, labels for the admin panel
        $labels = array(
            'name'                       => _x( 'FAQ Categories', 'Taxonomy General Name', 'tuts_faq' ),
            'singular_name'              => _x( 'FAQ Category', 'Taxonomy Singular Name', 'tuts_faq' ),
            'menu_name'                  => __( 'FAQ Categories', 'tuts_faq' ),
            'all_items'                  => __( 'All FAQ Cats', 'tuts_faq' ),
            'parent_item'                => __( 'Parent FAQ Cat', 'tuts_faq' ),
            'parent_item_colon'          => __( 'Parent FAQ Cat:', 'tuts_faq' ),
            'new_item_name'              => __( 'New FAQ Cat', 'tuts_faq' ),
            'add_new_item'               => __( 'Add New FAQ Cat', 'tuts_faq' ),
            'edit_item'                  => __( 'Edit FAQ Cat', 'tuts_faq' ),
            'update_item'                => __( 'Update FAQ Cat', 'tuts_faq' ),
            'separate_items_with_commas' => __( 'Separate items with commas', 'tuts_faq' ),
            'search_items'               => __( 'Search Items', 'tuts_faq' ),
            'add_or_remove_items'        => __( 'Add or remove items', 'tuts_faq' ),
            'choose_from_most_used'      => __( 'Choose from the most used items', 'tuts_faq' ),
            'not_found'                  => __( 'Not Found', 'tuts_faq' ),
        );
        $args = array(
            // use the labels above
            'labels'                     => $labels,
            // taxonomy should be hierarchial so we can display it like a category section
            'hierarchical'               => true,
            // again, make the taxonomy public (like the post type)
            'public'                     => true,
        );
        // the contents of the array below specifies which post types should the taxonomy be linked to
        register_taxonomy( 'tuts_faq_tax', array( 'tuts_faq' ), $args );
 
    }
 
    // hook into the 'init' action
    add_action( 'init', 'tuts_faq_tax', 0 );
 
}
 
if ( ! function_exists( 'tuts_faq_shortcode' ) ) {
 
    function tuts_faq_shortcode( $atts ) {
        extract( shortcode_atts(
                array(
                    // category slug attribute - defaults to blank
                    'category' => '',
                    // full content or excerpt attribute - defaults to full content
                    'excerpt' => 'false',
                ), $atts )
        );
         
        $output = '';
         
        // set the query arguments
        $query_args = array(
            // show all posts matching this query
            'posts_per_page'    =>   -1,
            // show the 'tuts_faq' custom post type
            'post_type'         =>   'tuts_faq',
            // show the posts matching the slug of the FAQ category specified with the shortcode's attribute
            'tax_query'         =>   array(
                array(
                    'taxonomy'  =>   'tuts_faq_tax',
                    'field'     =>   'slug',
                    'terms'     =>   $category,
                )
            ),
            // tell WordPress that it doesn't need to count total rows - this little trick reduces load on the database if you don't need pagination
            'no_found_rows'     =>   true,
        );
         
        // get the posts with our query arguments
        $faq_posts = get_posts( $query_args );
        $output .= '<div class="tuts-faq">';
         
        // handle our custom loop
        foreach ( $faq_posts as $post ) {
            setup_postdata( $post );
            $faq_item_title = get_the_title( $post->ID );
            $faq_item_permalink = get_permalink( $post->ID );
            $faq_item_content = get_the_content();
            if( $excerpt == 'true' )
                $faq_item_content = get_the_excerpt() . '<a href="' . $faq_item_permalink . '">' . __( 'More...', 'tuts_faq' ) . '</a>';
             
            $output .= '<div class="tuts-faq-item">';
            $output .= '<h2 class="faq-item-title">' . $faq_item_title . '</h2>';
            $output .= '<div class="faq-item-content">' . $faq_item_content . '</div>';
            $output .= '</div>';
        }
         
        wp_reset_postdata();
         
        $output .= '</div>';
         
        return $output;
    }
 
    add_shortcode( 'faq', 'tuts_faq_shortcode' );
 
}
 
function tuts_faq_activate() {
    tuts_faq_cpt();
    flush_rewrite_rules();
}
 
register_activation_hook( __FILE__, 'tuts_faq_activate' );
 
function tuts_faq_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'tuts_faq_deactivate' );
 
?>