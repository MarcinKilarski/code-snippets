<?php
namespace My_Site\Enqueue_Assets;

/**
 * Enqueue theme CSS and JavaScript in front-end only.
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_frontend_assets' );
function enqueue_frontend_assets()
{
    // theme path and url
    $theme_dir_path = CHILD_THEME_DIR_PATH;
    $theme_dir_url = CHILD_THEME_DIR_URL;

    // relative file path
    $js_file_path = '/assets/js/my-script.js';
    $css_file_path = '/assets/css/my-styles.css';

    // create version stamp based on when the files were last time modified
    // this way each version of the asset is safely cached
    $js_ver  = date("ymd-Gis", filemtime( $theme_dir_path . $js_file_path ));
    $css_ver = date("ymd-Gis", filemtime( $theme_dir_path . $css_file_path ));

    // enqueue assets
    wp_enqueue_script( 'my-script', $theme_dir_url . $js_file_path, ['jquery'], $js_ver );
    wp_enqueue_style( 'my-style', $theme_dir_url . $css_file_path, false, $css_ver );
}
