# My OAuth 2.0 Client

You can use this plugin as a template for WordPress user authentication via any OAuth 2.0 provider.

To use it you will need to do the following:

1. Update in the code values of the following variables:
   - $server_authentication_url
   - $server_token_url
   - $server_user_info_url
   - $client_id
   - $client_secret
   - $client_redirect_path_callback
   - $redirect_path_for_pending_users
   - $redirect_path_for_approved_users
   - $new_user_role
2. Check if the following function contain url parameters and request headers required by your OAuth 2.0 provider:
   - login_button_html()
   - getAccessToken()
   - getUserInfo()
3. Use [my_oauth_client_button] in the place you want the login button to appear

By default, new users are registered on your WP site with the role "pending-user".

The new user login name is randomly generated.

The plugin assumes that the OAuth provider will send you a user email and display name, which are then stored in your site's database.
