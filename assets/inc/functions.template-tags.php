<?php
/**
 * Multiple content blocks template tags
 */

/**
 * Display a block
 *
 * @param string $name The name of the block
 */
function the_block($name, $options = array( 'editor' => 'true')) {
	echo get_the_block($name, $options);
}

/**
 * Return a block
 *
 * @param string $name The name of the block
 */
function get_the_block($name, $options = array( 'editor' => 'true')) {
	if(!empty($name)) :
		global $post;
		mcb_register_block($post->ID,$name, $options);
		
		return apply_filters('the_content',get_post_meta($post->ID,'mcb-'.sanitize_title($name),true));
	endif;
}

/**
 * Register a block if it does not exist already
 *
 * @param int $post_id
 * @param string $name The name of the block
 */
function mcb_register_block($post_id,$name, $options = array( 'editor' => 'true')) {
	if(!mcb_block_exists($post_id,$name)) {
		$blocks = get_post_meta($post_id,'mcb-blocks',true);
		if(!is_array($blocks)) $blocks = array();
		
		$blocks[sanitize_title($name)] = array( 'name' => $name, 'options' => $options);
		
		update_post_meta($post_id,'mcb-blocks',$blocks);
	}
}

/**
 * Checks if a block already exists
 *
 * @param int $post_id
 * @param string $name The name of the block
 * @return bool
 */
function mcb_block_exists($post_id,$name) {
	$blocks = get_post_meta($post_id,'mcb-blocks',true);
	if(is_array($blocks)) :
		if($blocks[sanitize_title($name)] == $name) :
			return true;
		endif;
	endif;
	
	return false;
}

/**
 * Reset which blocks are used when visiting the page
 */
function mcb_refresh_blocks() {
	global $post;
	delete_post_meta($post->ID,'mcb-blocks');
}
add_action('wp_head','mcb_refresh_blocks');
?>