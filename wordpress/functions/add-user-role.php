<?php
namespace My_Site\User_Roles;

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
add_action( 'init', __NAMESPACE__ . '\\add_user_role' );
function add_user_role() {
    add_role(
        'event_manager',
        'Event manager',
        [
            'read'      => true,
            'level_0'   => true,
				]
    );
}

/**
 * Add capabilities
 */
add_action( 'admin_init', __NAMESPACE__ . '\\modify_user_role');
function modify_user_role() {
		$roles = [
			'administrator',
			'editor',
			'event_manager',
		];

		foreach( $roles as $the_role ) {
			$role = get_role( $the_role );

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

			// access to only assign resource accesses taxonomy
			$role->add_cap( 'manage_resource_access', false );
			$role->add_cap( 'edit_resource_access', false );
			$role->add_cap( 'delete_resource_access', false );
			$role->add_cap( 'assign_resource_access' );
			$role->add_cap( 'manage_resource_accessies', false );
			$role->add_cap( 'edit_resource_accessies', false );
			$role->add_cap( 'delete_resource_accessies', false );
			$role->add_cap( 'assign_resource_accessies' );

			// access to media library
			$role->add_cap( 'upload_files' );
		}
}
