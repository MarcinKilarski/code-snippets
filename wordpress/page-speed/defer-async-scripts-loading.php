<?php
namespace My_Site\Page_Speed;

/**
 * Page Speed Improvement: Defer loading all JavaScript files registered via the wp_enqueue_script() function
 */
add_filter('script_loader_tag', __NAMESPACE__ . '\\defer_async_scripts', 10);
function defer_async_scripts($tag)
{
    // Don't do anything if user is logged in as admin
    if (is_admin()) {
        return $tag;
    }

    // list of scripts to async, e.g. my-script.js
    $scripts_to_async = [];

    // async scripts
    foreach ($scripts_to_async as $async_script) {
        if (true == strpos($tag, $async_script)) {
            return str_replace(' src', ' async="async" src', $tag);
        }
    }

    // Do not add async to these scripts, e.g. my-script.js
    $scripts_to_exclude = [];

    // exclude scripts
    foreach ($scripts_to_exclude as $exclude_script) {
        if (true == strpos($tag, $exclude_script)) {
            return $tag;
        }
    }

    return str_replace(' src', ' defer="defer" src', $tag);
}
