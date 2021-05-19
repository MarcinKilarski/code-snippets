<?php
namespace My_Site\Taxonomies;

/**
 * Register new taxonomies
 */
add_action( 'init', __NAMESPACE__ . '\\register_new_taxonomies' );
function register_new_taxonomies()
{
    register_new_taxonomy([
        'single_name'          => 'Case category',
        'plural_name'          => 'Case categories',
        'assign_to_post_types' => ['case'],            			// if a CPT has multiple words in the name, use underscores instead of a space between words, e.g. 'team_member'
        'hierarchical'         => true,                			// true = like post categories, false = like post tags
        'show_in_rest'         => true,                			// Whether to include the taxonomy in the REST API.
        'show_ui'              => true,                			// Whether to generate and allow a UI for managing terms in this taxonomy in the admin
        'show_admin_column'    => true,                			// Whether to display a column for the taxonomy on its post type listing screens.
        'custom_cap'  				 => true,                			// Whether users need custom permissions to manage this taxonomy
				]);

    register_new_taxonomy([
        'single_name'          => 'Resource category',
        'plural_name'          => 'Resource categories',
        'assign_to_post_types' => ['resource'],             // if a CPT has multiple words in the name, use underscores instead of a space between words, e.g. 'team_member'
        'hierarchical'         => true,                     // true = like post categories, false = like post tags
        'show_in_rest'         => true,                     // Whether to include the taxonomy in the REST API.
        'show_ui'              => true,                     // Whether to generate and allow a UI for managing terms in this taxonomy in the admin
        'show_admin_column'    => true,                     // Whether to display a column for the taxonomy on its post type listing screens.
        'custom_cap'  			   => false,                    // Whether users need custom permissions to manage this taxonomy
    ]);
}

/**
 * Register a new taxonomy based on provided list of attributes
 *
 * @param array $config - List of attributes to register a new taxonomy
 */
function register_new_taxonomy($config)
{
    $assign_taxonomy_to_post_types = $config['assign_to_post_types']; // if a CPT has multiple words in the name, use underscores instead of a space between words, e.g. 'team_member'

    // taxonomy name
    $tax_single_name = $config['single_name'];
    $tax_plural_name = $config['plural_name'];

    // sanitized taxonomy names
    $tax_single_name_lowercase = strtolower(trim($tax_single_name));
    $tax_plural_name_lowercase = strtolower(trim($tax_plural_name));
    $tax_single_name_titlecase = ucwords($tax_single_name_lowercase);                // Convert the first character of each word to uppercase
    $tax_plural_name_titlecase = ucwords($tax_plural_name_lowercase);                // Convert the first character of each word to uppercase
    $tax_plural_name_slug      = str_replace(" ", "-", $tax_plural_name_lowercase);
    $tax_single_name_reg      = str_replace(" ", "_", $tax_single_name_lowercase);

    // taxonomy labels that will be displayed in the CMS
    $labels = [
        'name'              => esc_html_x( $tax_plural_name_titlecase, 'taxonomy general name', 'sage' ),
        'singular_name'     => esc_html_x( $tax_single_name_titlecase, 'taxonomy singular name', 'sage' ),
        'menu_name'         => esc_html__( $tax_plural_name_titlecase, 'sage' ),
        'search_items'      => esc_html__( 'Search ' . $tax_plural_name_titlecase, 'sage' ),
        'all_items'         => esc_html__( 'All ' . $tax_plural_name_titlecase, 'sage' ),
        'parent_item'       => esc_html__( 'Parent ' . $tax_single_name_titlecase, 'sage' ),
        'parent_item_colon' => esc_html__( 'Parent ' . $tax_single_name_titlecase . ':', 'sage' ),
        'edit_item'         => esc_html__( 'Edit ' . $tax_single_name_titlecase, 'sage' ),
        'update_item'       => esc_html__( 'Update ' . $tax_single_name_titlecase, 'sage' ),
        'add_new_item'      => esc_html__( 'Add New ' . $tax_single_name_titlecase, 'sage' ),
        'new_item_name'     => esc_html__( 'New ' . $tax_single_name_titlecase .' Name', 'sage' ),
    ];

    // Whether users need custom permissions to manage this taxonomy
    if ($config['custom_cap']) {
        $capabilities = [
            'manage_terms'  => 'manage_' . $tax_single_name_reg,
            'edit_terms'    => 'edit_' . $tax_single_name_reg,
            'delete_terms'  => 'delete_' . $tax_single_name_reg,
            'assign_terms'  => 'assign_' . $tax_single_name_reg
        ];
    }

    // Taxonomy configuration
    // more options can be found here: https://developer.wordpress.org/reference/functions/register_taxonomy/
    $args = [
        'hierarchical'      => $config['hierarchical'], 						// true = like post categories, false = like post tags
        'show_in_rest'      => $config['show_in_rest'], 						// Whether to include the taxonomy in the REST API.
        'show_ui'           => $config['show_ui'], 									// Whether to generate and allow a UI for managing terms in this taxonomy in the admin
        'show_admin_column' => $config['show_admin_column'], 				// Whether to display a column for the taxonomy on its post type listing screens.
        'rewrite'           => [ 'slug' => $tax_plural_name_slug ], // set the taxonomy URL slug
        'labels'            => $labels, 														// taxonomy labels that will be displayed in the CMS
        'capabilities'      => $capabilities ?? [],									// Whether to add custom capabilities to this taxonomy
    ];

     // register the taxonomy
     register_taxonomy( $tax_single_name_reg, $assign_taxonomy_to_post_types, $args );
}
