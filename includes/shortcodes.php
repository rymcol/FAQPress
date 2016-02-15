<?php
	
	if ( ! function_exists( 'faqpress_shortcode' ) ) {
 
    function faqpress_shortcode( $atts ) {
        extract( shortcode_atts(
                array(
                    // category slug attribute - defaults to blank
                    'category' => '',
                    // full content or excerpt attribute - defaults to full content
                    'excerpt' => 'false',
                ), $atts )
        );
         
        $output = '';
        
        // set the category query arguments
        $taxonomies = array(
	        'name' => 'faqpress_categories',
        );
        
        $terms_args = array(
	        'orderby' => 'count',
	        'hide_empty' => 0,
        );
        
		$categories = get_terms( $taxonomies, $terms_args );
		
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ){
		    echo '<ul class="faq-categories">';
		    foreach ( $categories as $term ) {
		        echo '<li>' . $term->name . '</li>';
		    }
		    echo '</ul>';
		}
        
        
         
        // set the post query arguments
        $post_query_args = array(
            // show all posts matching this query
            'posts_per_page'    =>   -1,
            // show the custom post type
            'post_type'         =>   'faqpressfaq',
            // show the posts matching the slug of the FAQ category specified with the shortcode's attribute
            'tax_query'         =>   array(
                    'taxonomy'  =>   'faqpress_categories',
                    'field'     =>   'slug',
                    'terms'     =>   $category,
            ),
            // tell WordPress that it doesn't need to count total rows - this little trick reduces load on the database if you don't need pagination
            'no_found_rows'     =>   true,
            'post_status' => 'publish',
        );
         
        // get the posts with our query arguments
	        $faq_posts = get_posts( $post_query_args );
        $output .= '<div class="faqpress">';
         
        // handle our custom loop
        foreach ( $faq_posts as $post ) {
            setup_postdata( $post );
            $faq_item_title = get_the_title( $post->ID );
            $faq_item_permalink = get_permalink( $post->ID );
            $faq_item_content = get_the_content();
            if( $excerpt == 'true' )
                $faq_item_content = get_the_excerpt() . '<a href="' . $faq_item_permalink . '">' . __( 'More...', 'faqpressfaq' ) . '</a>';
             
            $output .= '<div class="faqpress-item">';
            $output .= '<h2 class="faqrpess-item-title">' . $faq_item_title . '</h2>';
            $output .= '<div class="faqpress-item-content">' . $faq_item_content . '</div>';
            $output .= '</div>';
        }
         
        wp_reset_postdata();
         
        $output .= '</div>';
         
        return $output;
    }
 
    add_shortcode( 'faqpress', 'faqpress_shortcode' );
 
}
 