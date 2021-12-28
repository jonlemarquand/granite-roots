<?php
/*
* Buttons + Button blocks filter
* Removes buttons with no text and Buttons blocks with no children inside
*/
if ( ! is_admin() ) {
	add_filter( 'render_block', function( $content, $block ) {

		if($block['blockName'] == 'paws/button'){
			if(
				strpos(
					$content,
					'<a class="block-button primary" target="_self" rel="noopener"></a>',
				)
				||
				strpos(
					$content,
					'<a class="block-button secondary" target="_self" rel="noopener"></a>',
				)
			) {
				return;
			}
		}

		if($block['blockName'] == 'paws/buttons'){
			if(	preg_match('/<div class="block-buttons">\s*<\/div>/', $content) ) {
				return;
			}
		}
		return $content;

	}, 10, 2 );
}