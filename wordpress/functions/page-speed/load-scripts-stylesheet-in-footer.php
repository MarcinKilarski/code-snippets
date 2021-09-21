<?php
namespace My_Site\Page_Speed;

/**
 * Page speed: Load all registered scripts and stylesheets in the footer
 */
add_action('after_setup_theme', __NAMESPACE__ . '\\load_scripts_stylesheets_in_footer');
function load_scripts_stylesheets_in_footer()
{
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);

    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
}
