<?php
namespace My_Site\Plugins\WordPress_SEO;

/**
 * Pretty print all the Schema that Yoast SEO outputs on the page
 */
// add_filter( 'yoast_seo_development_mode', '__return_true' );

/**
 * Schema Event Class
 *
 * Helpful resources:
 *
 * - Testing: https://search.google.com/test/rich-results
 * - Yoast: https://developer.yoast.com/features/schema/integration-guidelines/
 * - Yoast: https://developer.yoast.com/features/schema/api/
 * - Google: https://developers.google.com/search/docs/data-types/event
 * - https://github.com/billerickson/Product-Review-Schema-for-Yoast-SEO/blob/7f895948d7d73dbefd9e503a155c9f562ae5f1d0/class-be-product-review.php#L6
 */
class Event_Scheme {
	/**
	 * A value object with context variables.
	 *
	 * @var WPSEO_Schema_Context
	 */
	public $context;

	/**
	 * Determines whether or not a piece should be added to the graph.
	 *
	 * @return bool
	 */
	public function is_needed() {
        // CPT name
		if ( 'webinar' === get_post_type() ) {
            return true;
        }
		return false;
	}

	/**
	 * Adds our Review piece of the graph.
	 *
	 * @return array $graph Review markup
	 */
	public function generate() {
        // get all ACF fields from a post
        $fields = get_fields();

        // Featured image
        $featured_image = get_the_post_thumbnail_url();
        $featured_image_fallback_img_id = 14674;
        if (!$featured_image) $featured_image = wp_get_attachment_url($featured_image_fallback_img_id);

        // Dates
        // $todays_date = time();
        $date_time_format = "Y-m-d\Th:i:s+01:00";
        $start_date = esc_html($fields['date-time']);
        $webinar_lenght = esc_html($fields['webinar_length']);
        $start_date_to_time = strtotime($start_date);
        $end_date_to_time = strtotime($start_date . ' + ' . $webinar_lenght . ' minutes');
        $start_date_formated = date($date_time_format, $start_date_to_time);
        $end_date_formated = date($date_time_format, $end_date_to_time);

        // Speakers info
        $speakers_output = [];
        $speakers_ids = $fields['speakers'];

        if ($speakers_ids):
            foreach ($speakers_ids as $speaker_id):
                $speaker_linkedin_url = get_field("linkedin_profile_url", $speaker_id);

                $speakers_output[] = [
                    "@type" => "Person",
                    "name" => get_the_title($speaker_id),
                    "sameAs" => $speaker_linkedin_url ?? "",
                ];
            endforeach;
        endif;

        // Terms
        $terms_output = '';
        $taxonomies = [
            'industry',
            'topics',
        ];

        foreach ($taxonomies as $index => $tax):
            $terms = strip_tags( get_the_term_list( $post->ID, $tax, '', ', ' ) );

            // if any terms are found add them to the output
            if ($terms):
                if ($terms_output):
                    $terms_output .= ', ' . $terms;
                else:
                    $terms_output .= $terms;
                endif;
            endif;
        endforeach;

        // Output
		$data = [
            "@type" => "Event",
            "name" => get_the_title(),
            "startDate" => $start_date_formated,
            "endDate" => $end_date_formated,
            "eventAttendanceMode" => "https://schema.org/OnlineEventAttendanceMode",
            "eventStatus" => "https://schema.org/EventScheduled",
            "location" => [
                "@type" => "VirtualLocation",
                "url" => "https://zoom.us/webinar/"
            ],
            "image" => [
                $featured_image,
            ],
            "description" => wp_kses_post( $fields['webinar_description'] ),
            "audience" => [
                "@type" => "Audience",
                "name" => "Professionals interested in learning more about " . $terms_output,
            ],
            "inLanguage" => [
                "@type" => "Language",
                "name" => "English"
            ],
            "isAccessibleForFree" => true,
            "offers" => [
                "@type" => "Offer",
                "url" => get_the_permalink(),
                "price" => "0",
                "priceCurrency" => "USD",
                "availability" => "https://schema.org/InStock",
                "validFrom" => get_the_date($date_time_format),
            ],
            "organizer" => [
                "@type" => "Organization",
                "name" => "Company Name",
                "url" => "https://company-site.com/"
            ],
            "performer" => $speakers_output,
        ];

		return $data;
	}
}

/**
 * Adds an event graph piece to the schema collector.
 *
 * @param array  $pieces  The current graph pieces.
 * @param string $context The current context.
 *
 * @return array The graph pieces.
 */
add_filter( 'wpseo_schema_graph_pieces',  __NAMESPACE__ . '\\add_event_schema_piece', 11, 2 );
function add_event_schema_piece( $pieces, $context ) {
    $pieces[] = new Event_Scheme( $context );

    return $pieces;
 }
