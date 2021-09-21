<?php

/**
 * Plugin Name:        My OAuth 2.0 Client
 * Description:        User authentication using custom OAuth provider.
 * Version:            1.0.0
 * Author:             My Author
 * Author URI:         https://my-site.com/
 *
 * License:            MIT License
 * License URI:        https://opensource.org/licenses/MIT
 */

namespace My_Plugin\OAuth;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class My_OAuth_Client
{
	private static $server_authentication_url = 'https://my-oauth-server.com/oauthserver/authorize';
	private static $server_token_url = 'https://my-oauth-server.com/oauthserver/token';
	private static $server_user_info_url = 'https://my-oauth-server.com/oauthserver/userinfo';

	private static $client_id = 'jdsakht432398hda9fh328h';
	private static $client_secret = 'asgdf3248thsauhd';
	private static $client_redirect_path_callback = '/callback';

	private static $redirect_path_for_pending_users = '/login/';
	private static $redirect_path_for_approved_users = '/';
	private static $new_user_role = 'pending-user';

	public function login_button_html()
	{
		if (!isset($_SESSION['state'])):
			session_start(); // get access to global $_SESSION variable

			// Create a state token to prevent request forgery.
			// Store the state token in the session for later validation.
			$state = bin2hex(random_bytes(128/8));
			$_SESSION['state'] = $state;
		endif;

		$button = '<form action="' . self::$server_authentication_url . '" method="get">
			<input type="hidden" name="client_id" value="' . self::$client_id . '">
			<input type="hidden" name="response_mode" value="query">
			<input type="hidden" name="response_type" value="code">
			<input type="hidden" name="redirect_uri" value="' . home_url() . self::$client_redirect_path_callback . '">
			<input type="hidden" name="state" value="' . $_SESSION['state'] . '">
			<button type="submit" class="button-one">Log in</button>
		</form>';

		return $button;
	}

	public function oauth_login_validate()
	{
		if (session_id() == '' || !isset($_SESSION))
			session_start(); // get access to global $_SESSION variable

		if (
			isset( $_REQUEST['code'] ) &&
			isset( $_REQUEST['state'] )
		): // run when server returns authentication code
			if ($_REQUEST['state'] !== $_SESSION['state']):
				exit('State token does not match');
				return;
			endif ;

			$code = $_REQUEST['code'];
			$access_token = self::getAccessToken($code);
			$user_info = self::getUserInfo($access_token);
			$user_email = $user_info['email'];

			self::userValidation($user_info);
			self::loginUser($user_email);
			self::redirectAfterLogin( $user_email );

			exit();
		endif;
	}

	private static function getAccessToken($code)
	{
		$response  = wp_remote_post( self::$server_token_url, array(
			'method'   => 'POST',
			'timeout'   => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'  => true,
			'headers'   => array(),
			'body'    => array(
				'client_id'  => self::$client_id,
				'client_secret'  => self::$client_secret,
				'code'  => $code,
			),
			'cookies'   => array(),
			'sslverify'  => true
		) );

		$response = $response['body'] ;
		self::checkForError($response);
		$content = json_decode($response,true);

		if (isset($content["error_description"])):
			exit($content["error_description"]);
		elseif (isset($content["error"])):
			exit($content["error"]);
		elseif (isset($content["access_token"])):
			$access_token = $content["access_token"];
		else:
			exit('Invalid response received from OAuth Provider. Contact your administrator for more details.');
		endif;

		return $access_token;
	}

	private static function getUserInfo($access_token)
	{
		$response = wp_remote_post( self::$server_user_info_url, array(
			'method'   => 'GET',
			'timeout'   => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'  => true,
			'headers'   => array(
				'Authorization' => 'Bearer '.$access_token
			),
			'cookies'   => array(),
			'sslverify'  => true
		) );

		self::checkForError($response);

		$response_body = wp_remote_retrieve_body($response);

		self::checkForError($response_body);

		$content = json_decode($response_body, true);

		if (isset($content["error_description"])):
			exit($content["error_description"]);
		elseif (isset($content["error"])):
			exit($content["error"]);
		endif;

		return $content;
	}

	private static function userValidation($user_info)
	{
		if (!isset( $user_info['username'] ) && !isset( $user_info['email'] ))
			exit('User details are missing');

		$user_email = $user_info['email'];
		$display_name = $user_info['username'];

		if (!email_exists( $user_email )):
			$role = (self::role_exists(self::$new_user_role) ? self::$new_user_role: 'subscriber');

			self::registerUser($user_email, $display_name, $role);
		else:
			self::updateUser($user_email, $display_name);
		endif;
	}

	private static function role_exists($role)
	{
		return !empty($role)
			 ? $GLOBALS['wp_roles']->is_role($role)
			 : false;
	}

	private static function registerUser($user_email, $display_name, $role)
	{
		$username = self::generate_username();
		$password = bin2hex( random_bytes(128/8) );

		$user_registration_result = wp_insert_user( array(
			'user_login' => $username,
			'user_pass' => $password,
			'user_email' => $user_email,
			'nickname' => $display_name,
			'display_name' => $display_name,
			'role' => $role
		));

		self::checkForError($user_registration_result);
	}

	private static function updateUser($user_email, $display_name)
	{
		$user_id = self::getUser( $user_email )->ID;

		$user_update_result = wp_update_user( array(
			'ID' => $user_id,
			'nickname' => $display_name,
			'display_name' => $display_name,
		));

		self::checkForError( $user_update_result );
	}

	private static function generate_username()
	{
		$username = bin2hex( random_bytes(64/8) );

		return username_exists( $username )
			? self::generate_username()
			: $username;
	}

	private static function loginUser( $user_email )
	{
		$user = self::getUser( $user_email );
		$user_id = $user->ID;

		// login as this user
		wp_set_current_user( $user_id, $user_email );
		wp_set_auth_cookie( $user_id );
		do_action( 'wp_login', $user_email, $user );
	}

	private static function redirectAfterLogin( $user_email )
	{
		$user = self::getUser( $user_email );

		return ( in_array( 'pending-user', $user->roles ) )
			? wp_redirect( home_url() . self::$redirect_path_for_pending_users )
			: wp_redirect( home_url() . self::$redirect_path_for_approved_users );
	}

	private static function getUser( $user_email )
	{
		$user = get_user_by( 'email', $user_email );
		if (!$user)
			exit('User not found');

		return $user;
	}

	private static function checkForError( $result )
	{
		if( is_wp_error( $result ) ):
			$error = $result->get_error_message();
			exit( $error );
		endif;
	}
}
add_action( 'init', __NAMESPACE__ . '\\My_OAuth_Client::oauth_login_validate' );

// Register shortcode
add_shortcode('my_oauth_client_button', __NAMESPACE__ . '\\My_OAuth_Client::login_button_html');
