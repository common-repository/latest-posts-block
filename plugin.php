<?php
/**
 * Plugin Name:       Latest Posts Block
 * Plugin URI:        http://ashathemes.com/
 * Description:       This plugin will enable a latest post block widget. If you want to use this plugin please active a theme which is developed by ashathemes.
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           1.0.1
 * Author:            Ashathemes
 * Author URI:        https://profiles.wordpress.org/ashathemes
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       latest-posts-block
 * Domain Path:       /languages
 *
 * @package           latest-posts-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */

function latest_posts_block($attributes) {
	$args = array(
		'posts_per_page' => $attributes['numberOfPosts'],
		'post_status' => 'publish',
		'order' => $attributes['order'],
		'orderby' => $attributes['orderBy'],
	);
	$recent_posts = get_posts($args);
	
	$posts = '<ul ' . get_block_wrapper_attributes() . '>';
	foreach($recent_posts as $post) {
		$title = get_the_title($post);
		$title = $title ? $title : __('(No title)','latest-posts-block');
		$permalink = get_permalink( $post );
		$excerpt = get_the_excerpt( $post );

		$posts .= '<li>';

		if($attributes["displayFeaturedImage"] && has_post_thumbnail( $post )) {
			$posts .= get_the_post_thumbnail( $post, 'large' );
		}
		$posts .= '<h5><a href="' . esc_url($permalink) . '">' . esc_html($title) . '</a></h5>';
		$posts .= '<time datetime="' . esc_attr( get_the_date('c', $post)) . '">' . esc_html( get_the_date('', $post)) . '</time>';

		if(!empty($excerpt)) {
			$posts .= '<p>' . esc_html($excerpt) . '</p>';
		}

		$posts .= '</li>';
	}
	$posts .= '</ul>';

	return $posts;
}
function latest_posts_block_init() {
	register_block_type_from_metadata( __DIR__, array(
		'render_callback' => 'latest_posts_block'
	) );
}
add_action( 'init', 'latest_posts_block_init' );
