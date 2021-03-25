<?php
namespace My_Site\Page_Speed;

/**
 * Page Speed Improvement: Asynchronously load all JavaScript files registered via the wp_enqueue_script() function
 */
add_filter('script_loader_tag', __NAMESPACE__ . '\\defer_async_scripts', 10, 3);
function defer_async_scripts($tag, $handle, $src)
{
    // Finding script handles
    // log handles of all registered scripts
    // error_log('$handle: ' . $handle);

    // add script handle to every the script ID
    // return '<script id="' . $handle . '" src="' . $src . '"></script>';

    // Don't do anything if user is logged in as admin
    if (is_admin()) {
        return $tag;
    }

    // list of scripts to defer, e.g. my-script
    $scripts_to_defer = [];

    // defer scripts
    foreach ($scripts_to_defer as $defer_script) {
        if (true == strpos($tag, $defer_script)) return '<script defer="defer" src="' . $src . '"></script>';
    }

    // Do not add async to these scripts, e.g. my-script.js
    $scripts_to_exclude = [];

    // exclude scripts
    foreach ($scripts_to_exclude as $exclude_script) {
        if (true == strpos($tag, $exclude_script)) return $tag;
    }

    return '<script async="async" src="' . $src . '"></script>';
}
