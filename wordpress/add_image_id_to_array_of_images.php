<?php
namespace My_Site\Once_Off;

/**
 * Add image ID to array of images
 * to update the image alt text later on
 *
 * How to convert a Google Sheet into JSON http://blog.pamelafox.org/2013/06/exporting-google-spreadsheet-as-json.html
 * Then manually convert it into an array with associated arrays by modifying the syntax
 */
add_action( 'init', __NAMESPACE__ . '\\add_image_id_to_array_of_images' );
function add_image_id_to_array_of_images() {
	// list of images with url and alt text
	$image_arrays = [
		[
			'image-url' => 'http://o9solutions.mdev/wp-content/uploads/2020/05/image1.svg',
			'altText' => 'My alt text number 1',
		],
		[
			'image-url' => 'http://o9solutions.mdev/wp-content/uploads/2021/01/image2.jpg',
			'altText' => 'My alt text number 2',
		],
		[
			'image-url' => 'http://o9solutions.mdev/wp-content/uploads/2020/03/image3.png',
			'altText' => 'My alt text number 3',
		],
	];

	// list of images with id, url and alt text
	$new_image_array = [];

	foreach ($image_arrays as $image_array) {
		$get_image_id = attachment_url_to_postid( $image_array['image-url'] );

		// if the image ID was found, add image it to the array item
		if ($get_image_id) {
			$image_array['id'] = $get_image_id;
			continue;
		}

		// if you found the image id, add the image into a new array
		$new_image_array[] = $image_array;
	}

	// print the new array in the error log
	error_log('$new_image_array' . ': ' . print_r($new_image_array, 1));
}
