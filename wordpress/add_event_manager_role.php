<?php
namespace My_Site\User_Roles\Event_Manager;

/**
 * Remove role
 */
// remove_role( 'event_manager' );

/**
 * Add new role
 *
 * - list of user capabilities: https://wordpress.org/support/article/roles-and-capabilities/
 * - list of default roles with their cap: https://isabelcastillo.com/list-roles-capabilities-wordpress
 * - deep dive: https://kinsta.com/blog/wordpress-user-roles/
 */
add_action( 'init', __NAMESPACE__ . '\\add_role' );
function add_role() {
    add_role(
        'event_manager',
        'Event manager',
        array(
            'read'      => true,
            'level_0'   => true,
        )
    );
}

/**
 * Add capabilities
 */
add_action( 'admin_init', __NAMESPACE__ . '\\modify_role');
function modify_role() {
    $role = get_role( 'event_manager' );

    // access to webinars CPT
    $role->add_cap( 'read_webinar');
    $role->add_cap( 'edit_webinar' );
    $role->add_cap( 'read_private_webinars' );
    $role->add_cap( 'edit_webinars' );
    $role->add_cap( 'edit_others_webinars' );
    $role->add_cap( 'edit_published_webinars' );
    $role->add_cap( 'publish_webinars' );
    $role->add_cap( 'delete_others_webinars' );
    $role->add_cap( 'delete_private_webinars' );
    $role->add_cap( 'delete_published_webinars' );

    // access to speakers CPT
    $role->add_cap( 'read_speaker');
    $role->add_cap( 'edit_speaker' );
    $role->add_cap( 'read_private_speakers' );
    $role->add_cap( 'edit_speakers' );
    $role->add_cap( 'edit_others_speakers' );
    $role->add_cap( 'edit_published_speakers' );
    $role->add_cap( 'publish_speakers' );
    $role->add_cap( 'delete_others_speakers' );
    $role->add_cap( 'delete_private_speakers' );
    $role->add_cap( 'delete_published_speakers' );

    // access to topics taxonomy
    $role->add_cap( 'manage_topic' );
    $role->add_cap( 'edit_topic' );
    $role->add_cap( 'delete_topic' );
    $role->add_cap( 'assign_topic' );
    $role->add_cap( 'manage_topics' );
    $role->add_cap( 'edit_topics' );
    $role->add_cap( 'delete_topics' );
    $role->add_cap( 'assign_topics' );

    // access to media library
    $role->add_cap( 'upload_files' );
}
