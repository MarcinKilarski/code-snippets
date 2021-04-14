<?php
namespace My_Site\Images;

/**
 * Update images alt text
 */
// add_action( 'init', __NAMESPACE__ . '\\update_images_alt_text' );
function update_images_alt_text() {
	// list of images with url and alt text
	$image_arrays =  [
		[
				"image-url" => "https://example.com/wp-content/uploads/2020/05/nestle.svg",
				'alt-text' => 'My alt text number 1',
				"id" => 9792
		],
		[
				"image-url" => "https://example.com/wp-content/uploads/2020/03/ge-2.png",
				'alt-text' => 'My alt text number 2',
				"id" => 8497
		],
		[
				"image-url" => "https://example.com/wp-content/uploads/2020/03/estee-1-1.jpg",
				'alt-text' => 'My alt text number 3',
				"id" => 8707
		]
	];

	foreach ($image_arrays as $image) {
		$id = $image["id"];
		$alt = $image["altText"];

		// set the image Alt-Text
		update_post_meta($id, '_wp_attachment_image_alt', $alt);

		// log the updated image meta
		error_log('$new_image_array' . ': ' . print_r( get_post_meta( $id, '_wp_attachment_image_alt', true), 1));
	}

	error_log('Done');
}
