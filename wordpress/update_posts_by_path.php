<?php
namespace My_Site\Update_Posts_By_Path;

/**
 * Update posts based on their path
 */
function mm_update_all_post_types_by_path() {
	// data should be an array containing arrays of post
	$data = [
		[
			"url" => "https://mysite.com/mypage/",
			"content_to_update"	=> "My page meta description..."
		],
	];
	// mm_log($data, '$data');

	// get post types that are on the site
	$post_types = get_post_types( ['public'   => true], 'names', 'and' );
	$post_types_array = [];

	if ( $post_types ) { // If there are any custom public post types.
		foreach ( $post_types  as $post_type ) {
			$post_types_array[] = $post_type;
		}
	}
	// mm_log($post_types, '$post_types');
	// mm_log($post_types_array, '$post_types_array');

	foreach($data as $post) {
		$url_page = 'https://mysite.com/';
		$url_news = 'https://mysite.com/news/';
		$url_solutions = 'https://mysite.com/solutions/';

		if(strpos($post['url'], $url_solutions) === 0) {
			$path = str_replace( $url_solutions, '', $post['url']);
			$post_type = 'solutions';
		} else if (strpos($post['url'], $url_news) === 0) {
			$path = str_replace( $url_news, '', $post['url']);
			$post_type = 'post';
		} else if (strpos($post['url'], $url_page) === 0) {
			$path = str_replace( $url_page, '', $post['url']);
			$post_type = 'page';
		}

		// get post ID
		$post_id = get_page_by_path( $path, OBJECT, $post_type)->ID;

		// if the post ID was not found, log data that will help understand why is that the case
		if (!$post_id) {
			// mm_log($post_id, '$post_id');
			// mm_log($path, '$path');
			// mm_log($post['url'], '$post[url]');
			continue; // continue to the next post without updating this one
		}

		// update the
		$data_to_update = $post['content_to_update'];
		if ($data_to_update) $updated_desc = update_post_meta($post_id, '_yoast_wpseo_metadesc', $data_to_update);

		// mm_log($updated_desc, '$updated_desc'); // returns nothing if the meta was not updated because it might be the same in the post

		// test if you see new data on the site
		// $get_post_meta = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
		// mm_log($data_to_update, '$data_to_update');
		// mm_log($get_post_meta, '$get_post_meta');
	}
}
add_action( 'shutdown', 'mm_update_all_post_types_by_path' );
