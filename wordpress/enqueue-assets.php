<?php
namespace My_Site\Enqueue_Assets;

/**
 * Enqueue theme CSS and JavaScript in front-end only.
 */
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_frontend_assets' );
function enqueue_frontend_assets()
{
    // create my own version codes based on when the files were last time modified
    $my_js_ver  = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'my-script.js' ));
    $my_css_ver = date("ymd-Gis", filemtime( plugin_dir_path( __FILE__ ) . 'my-style.css' ));

    wp_enqueue_script( 'my-script', plugins_url( 'my-script.js', __FILE__ ), ['jquery'], $my_js_ver );
    wp_register_style( 'my-style', plugins_url( 'my-style.css', __FILE__ ), false, $my_css_ver );
    wp_enqueue_style ( 'style' );
}
