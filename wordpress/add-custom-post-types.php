<?php
namespace My_Site\Custom_Post_Types;

/**
 * Register new CPTs
 */
add_action( 'init', __NAMESPACE__ . '\\register_new_custom_post_types' );
function register_new_custom_post_types()
{
    register_new_custom_post_type([
        'single_name'  => 'Case',
        'plural_name'  => 'Cases',
        'menu_icon'    => 'dashicons-chart-bar',    // https://developer.wordpress.org/resource/dashicons/
        'public'       => true,                     // Whether a post type is intended for use publicly either via the admin interface or by front-end users.
        'show_in_rest' => false,                     // Whether to include the CPT in the REST API.
        'supports'     => [                         // enabled features for this CPT posts
            'title',
            'revisions',
            'thumbnail', // featured image
            // 'excerpt',
            // 'editor',
            // 'author',
            // 'comments',
        ],
    ]);

    register_new_custom_post_type([
        'single_name'  => 'Team member',
        'plural_name'  => 'Team members',
        'menu_icon'    => 'dashicons-groups',       // https://developer.wordpress.org/resource/dashicons/
        'public'       => true,                     // Whether a post type is intended for use publicly either via the admin interface or by front-end users.
        'show_in_rest' => false,                     // Whether to include the CPT in the REST API.
        'supports'     => [                         // enabled features for this CPT posts
            'title',
            'revisions',
            'thumbnail', // featured image
            // 'excerpt',
            // 'editor',
            // 'author',
            // 'comments',
        ],
    ]);
}

/**
 * Register a new CPT based on provided list of attributes
 *
 * @param array $config - List of attributes to register a new CPT
 */
function register_new_custom_post_type($config)
{
    // CPT name
    $cpt_single_name = $config['single_name'];
    $cpt_plural_name = $config['plural_name'];

    // sanitized CPT names
    $cpt_single_name_lowercase = strtolower(trim($cpt_single_name));
    $cpt_plural_name_lowercase = strtolower(trim($cpt_plural_name));
    $cpt_single_name_titlecase = ucwords($cpt_single_name_lowercase);                // Convert the first character of each word to uppercase
    $cpt_plural_name_titlecase = ucwords($cpt_plural_name_lowercase);                // Convert the first character of each word to uppercase
    $cpt_plural_name_slug      = str_replace(" ", "-", $cpt_plural_name_lowercase);
    $cpt_single_name_reg      = str_replace(" ", "_", $cpt_single_name_lowercase);

    // CPT labels that will be displayed in the CMS
    $labels = [
        'name'                  => esc_html_x( $cpt_plural_name_titlecase, 'Post type general name', 'sage' ),
        'singular_name'         => esc_html_x( $cpt_single_name_titlecase, 'Post type singular name', 'sage' ),
        'menu_name'             => esc_html_x( $cpt_plural_name_titlecase, 'Admin Menu text', 'sage' ),
        'name_admin_bar'        => esc_html_x( $cpt_single_name_titlecase, 'Add New on Toolbar', 'sage' ),
        'add_new'               => esc_html__( 'Add New ' . $cpt_single_name_titlecase, 'sage' ),
        'add_new_item'          => esc_html__( 'Add New ' . $cpt_single_name_titlecase, 'sage' ),
        'new_item'              => esc_html__( 'New ' . $cpt_single_name_titlecase, 'sage' ),
        'edit_item'             => esc_html__( 'Edit ' . $cpt_single_name_titlecase, 'sage' ),
        'view_item'             => esc_html__( 'View ' . $cpt_single_name_titlecase, 'sage' ),
        'all_items'             => esc_html__( 'All ' . $cpt_plural_name_titlecase, 'sage' ),
        'search_items'          => esc_html__( 'Search ' . $cpt_plural_name_titlecase, 'sage' ),
        'parent_item_colon'     => esc_html__( 'Parent ' . $cpt_plural_name_titlecase . ':', 'sage' ),
        'not_found'             => esc_html__( 'No ' . $cpt_plural_name_lowercase . ' found.', 'sage' ),
        'not_found_in_trash'    => esc_html__( 'No ' . $cpt_plural_name_lowercase . ' found in Trash.', 'sage' ),
        'featured_image'        => esc_html_x( $cpt_single_name_titlecase . ' Main Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'sage' ),
        'set_featured_image'    => esc_html_x( 'Set main image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'sage' ),
        'remove_featured_image' => esc_html_x( 'Remove main image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'sage' ),
        'use_featured_image'    => esc_html_x( 'Use as main image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'sage' ),
        'archives'              => esc_html_x( $cpt_single_name . ' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'sage' ),
        'insert_into_item'      => esc_html_x( 'Insert into ' . $cpt_single_name_lowercase, 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'sage' ),
        'uploaded_to_this_item' => esc_html_x( 'Uploaded to this ' . $cpt_single_name_lowercase, 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'sage' ),
        'filter_items_list'     => esc_html_x( 'Filter ' . $cpt_plural_name_lowercase . ' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'sage' ),
        'items_list_navigation' => esc_html_x( $cpt_plural_name . ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'sage' ),
        'items_list'            => esc_html_x( $cpt_plural_name . ' list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'sage' ),
    ];

    // more options can be found here: https://developer.wordpress.org/reference/functions/register_post_type/
    $args = [
        'public'       => $config['public'],                     // Whether a post type is intended for use publicly either via the admin interface or by front-end users.
        'show_in_rest' => $config['show_in_rest'],
        'menu_icon'    => $config['menu_icon'],                  // https://developer.wordpress.org/resource/dashicons/
        'supports'     => $config['supports'],
        'labels'       => $labels,
        'rewrite'      => [ 'slug' => $cpt_plural_name_slug ],   // Triggers the handling of rewrites for this post type
    ];

    // register the CPT
    register_post_type( $cpt_single_name_reg , $args );
}
