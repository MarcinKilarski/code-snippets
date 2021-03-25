<?php
namespace o9\Page_Speed;

/**
 * Preload fonts to make the text appear faster
 */
add_action( 'wp_head', __NAMESPACE__ . '\\preload_fonts', 10 );
function preload_fonts() {
    // Preload custom fonts
    // crossorigin="anonymous" is required for the preload to work correctly
    ?>
    <link rel="preload" as="font" type="font/woff2" crossorigin="anonymous" href="/uploads/font/my-font-Bold.woff2">
    <link rel="preload" as="font" type="font/woff2" crossorigin="anonymous" href="/uploads/font/my-font-Regular.woff2">
    <link rel="preload" as="font" type="font/woff2" crossorigin="anonymous" href="/uploads/font/my-font-Light.woff2">
    <?php
}
