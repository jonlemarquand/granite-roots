<?php
/*
* Filter buttons to add post url dynamically by ID
*/
add_filter( 'render_block', function( $content, $block ) {

    if ( $block['blockName'] == 'paws/button' ) {

		if ( isset( $block['attrs']['linkValue']['id'] ) ) {
			$post_id = $block['attrs']['linkValue']['id'];
			$post_link = get_permalink($post_id);

			if (false === strpos( $content, 'href=""' ) ) { // Empty href
				$content = str_replace( 'href=""', 'href="' . $post_link . '"', $content ); // Add href
			}
			else {
				$content = str_replace( 'href="', 'href="' . $post_link . '" href="', $content ); // Add href
			}	
		}

		return $content; 

	} else {

		return $content; 

	}
    
}, 10, 2 );