<?php

if ( ! function_exists( 'faqpress_categories' ) ) {
 
    // Create Some Categories
    function faqpress_categories() {
 
        // again, labels for the admin panel
        
        $args = array(
	       	'show_ui' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
			'label' => 'FAQ Categories',	// taxonomy name
			'query_var' => true,	// enable taxonomy-specific querying
			'rewrite' => array( 'slug' => 'faq-category' ),	// pretty permalinks
            'public' => true, // make public!
        );
        
        // the contents of the array below specifies which post types should the taxonomy be linked to
        register_taxonomy( 'faqpress_categories', array( 'faqpressfaq' ), $args );
 
    }
 
    // hook into the 'init' action
    add_action( 'init', 'faqpress_categories', 0 );
 
}
