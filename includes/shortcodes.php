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
	        
	        // set the post query arguments
			$post_query_args = array(
			    'posts_per_page'    =>   -1, //all of the posts
			    'post_type'         =>   'faqpressfaq', //only within the plugin
			    // show the posts matching the slug of the FAQ category specified with the shortcode's attribute
			    'tax_query'         =>   array(
				    array(
			            'taxonomy'  =>   'faqpress_categories',
			            'field'     =>   'slug',
			            'terms'     =>   $category,
						)),
			    'no_found_rows'     =>   true,
			    'post_status' => 'publish',
			    'orderby' => 'date',
			    'order' => 'ASC',
			);
			 
			// get the posts with our query arguments
			$faq_posts = new WP_Query( $post_query_args );
			$postOutput = '<div id="faqpress">';
			$postOutput .= '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
			 
			// loop it!
			while ( $faq_posts->have_posts() ) {
				$faq_posts->the_post();
			    $faq_item_title = get_the_title();
			    $faq_item_permalink = get_the_permalink();
			    $faq_item_content = get_the_content();
			    if( $excerpt == 'true' )
			        $faq_item_content = $faq_posts->the_excerpt() . '<a href="' . $faq_item_permalink . '">' . __( 'More...', 'faqpressfaq' ) . '</a>';
				
				$postCount = $faq_posts->current_post + 1;
				 
			 	$postOutput .= '<div class="panel panel-default"><div class="panel-heading" role="tab"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$postCount.'" aria-expanded="false" aria-controls="collapse'.$postCount.'">';
			    $postOutput .= $faq_item_title;
			    $postOutput .= '</a></h4></div>';
			    $postOutput .= '<div id="collapse'.$postCount.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne"><div class="panel-body">';
			    $postOutput .= $faq_item_content;
			    $postOutput .= '</div></div></div>';
			}
			 
			wp_reset_postdata();
			 
			$postOutput .= '</div></div>';
	        
	        echo($postOutput);

		}
 
    add_shortcode( 'faqpress', 'faqpress_shortcode' );
 
	}
 