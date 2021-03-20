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
 * - Example: https://github.com/billerickson/Product-Review-Schema-for-Yoast-SEO/blob/7f895948d7d73dbefd9e503a155c9f562ae5f1d0/class-be-product-review.php#L6
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
        // add it only on the page with ID 15848 (event page)
		if (is_page(15848)) {
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
		$data = [
            "@type" => "Event",
            "name" => "Star Wars Family Get Together",
            "startDate" => "2021-04-19T16:00:00+01:00",
            "endDate" => "2021-04-21T16:00:00+01:00",
            "eventAttendanceMode" => "https://schema.org/OnlineEventAttendanceMode",
            "eventStatus" => "https://schema.org/EventScheduled",
            "location" => [
                "@type" => "VirtualLocation",
                "url" => "https://virtual.hubilo.com/"
            ],
            "image" => [
                "https://lumiere-a.akamaihd.net/v1/images/star-wars-the-rise-of-skywalker-theatrical-poster-1000_ebc74357.jpeg?region=1%2C318%2C999%2C499",
                "https://lumiere-a.akamaihd.net/v1/images/solo-a-star-wars-story-theatrical-poster-2_f4af9297.jpeg?region=0%2C397%2C1298%2C646&width=1200",
                ],
            "description" => "Join hundreds of Star Wars fans around the world to celebrate the series and meet same-minded people.",
            "audience" => [
                "@type" => "Audience",
                "name" => "Sci-fi enthusiasts, Star Wars fans"
            ],
            "inLanguage" => [
                "@type" => "Language",
                "name" => "English"
            ],
            "isAccessibleForFree" => true,
            "offers" => [
                "@type" => "Offer",
                "url" => "https://www.starwars.com/films",
                "price" => "0",
                "priceCurrency" => "USD",
                "availability" => "https://schema.org/InStock",
                "validFrom" => "2021-04-21T16:00"
            ],
            "organizer" => [
                "@type" => "Organization",
                "name" => "Star Wars",
                "url" => "https://www.starwars.com"
            ],
            "performer" => [
                [
                    "@type" => "Person",
                    "name" => "Princess Leia",
                    "sameAs" => "https://www.linkedin.com/in/9823673298378903/",
                ],
                [
                    "@type" => "Person",
                    "name" => "Luke Skywalker",
                    "sameAs" => "https://www.linkedin.com/in/2902370382739826398/",
                ],
            ],
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
