<?php
namespace o9\Page_Speed;

/*
    Note: Use https://polyfill.io for adding any polyfills to the site that that service has in its library
    It sends an empty file to browsers which does not require a polyfill to make a functionality work, but it sends the needed code to browsers that need it. How to use it: https://docs.google.com/document/d/1HKAqo03cxyygZV9OV_PZlfmblonRBShheCoZNiAU5RU/edit#heading=h.ac7sinsnvoam
    Required Polyfills and where they are needed:
    - CustomEvent - common.js
    - IntersectionObserver - submenu.js , animations.js
    - NodeList.prototype.forEach - form.js
    - Object.assign - opening modals
*/
add_action( 'wp_head', __NAMESPACE__ . '\\add_polyfill', 10 );
function add_polyfill() {
    ?>
    <script defer src="https://polyfill.io/v3/polyfill.min.js?features=CustomEvent%2CIntersectionObserver%2CNodeList.prototype.forEach%2CObject.assign"></script>
    <?php
}
