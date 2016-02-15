<?php 

if ( ! function_exists( 'faqpress_cpt' ) ) {
 
// Register our custom post type!
    function faqpress_cpt() {
	    
	    // Name Things!
		$labels = array(
			'name' => _x('FAQPress', 'post type general name'),
			'singular_name' => _x('Question', 'post type singular name'),
			'add_new' => _x('Add Question', 'photo item'),
			'add_new_item' => __('Add New Question'),
			'all_items' => __('Manage Questions'),
			'edit_item' => __('Edit Question'),
			'new_item' => __('New Question'),
			'view_item' => __('View Question'),
			'search_items' => __('Search Questions'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		);
		
		$args = array(
		'public' => true,
		'labels'  => $labels,
		'has_archive' => true,
        'menu_icon' => 'dashicons-book-alt',
		'taxonomies' => array( 'faqpress_categories'),
		'capability_type' => 'post',
		'rewrite' => array('slug'=> 'faq'),
		'menu_icon' => 'dashicons-book-alt',
		'supports' => array(
				'title',
				'excerpt',
				'editor',
				'thumbnail',
				'page-attributes',
				'revisions'
			),
		);
		
        register_post_type( 'faqpressfaq', $args );
 
    }
 
    // Initialize the New Post Type
    add_action( 'init', 'faqpress_cpt', 0 );
 
}

if ( ! function_exists( 'faqpress_categories' ) ) {
 
    // Create Categories
    function faqpress_categories() {
        
        $args = array(
	       	'show_ui' => true,
			'show_admin_column' => true,
			'hierarchical' => true,
			'label' => 'FAQ Categories',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'faq-category' ),
            'public' => true, // make public!
        );
        
        register_taxonomy( 'faqpress_categories', array( 'faqpressfaq' ), $args );
 
    }
 
    // hook into the 'init' action
    add_action( 'init', 'faqpress_categories', 0 );

 
}