<?php
namespace My_Site\Page_Speed;

/**
 * Page speed: Minimize HTML files
 *
 * Parameters:
 * - compare size of the original HTML and the minified one
 * - minimize inline CSS
 * - minimize inline JavaScript
 * - remove comments from HTML
 * - minimize HTML
 */
 class FLHM_HTML_Compression
 {
     protected $flhm_info_comment = false; // it shows at the bottom of the html how much smaller is the file
     protected $flhm_compress_css = true;
     protected $flhm_compress_js = false; // 7-1-2021 Martin Kilarski: it causes issue with /complianz-gdpr-premium/integrations/plugins/gravity-forms.php:line=49 comments which us '//' instead of '/* */'
     protected $flhm_remove_html_comments = true;
     protected $html;

     public function __construct($html)
     {
         if (!empty($html)) {
             $this->flhm_parseHTML($html);
         }
     }

     public function __toString()
     {
         return $this->html;
     }

     protected function flhm_bottomComment($raw, $compressed)
     {
         $raw = strlen($raw);
         $compressed = strlen($compressed);
         $savings = ($raw - $compressed) / $raw * 100;
         $savings = round($savings, 2);
         return '<!--HTML compressed, size saved ' . $savings . '%. From ' . $raw . ' bytes, now ' . $compressed . ' bytes-->';
     }

     protected function flhm_minifyHTML($html)
     {
         $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
         preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
         $overriding = false;
         $raw_tag = false;
         $html = '';

         foreach ($matches as $token) {
             $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
             $content = $token[0];

             if (is_null($tag)) {
                 if (!empty($token['script'])) {
                     $strip = $this->flhm_compress_js;
                 } else if (!empty($token['style'])) {
                     $strip = $this->flhm_compress_css;
                 } else if ($content == '<!--wp-html-compression no compression-->') {
                     $overriding = !$overriding;
                     continue;
                 } else if ($this->flhm_remove_html_comments) {
                     if (!$overriding && $raw_tag != 'textarea') {
                         $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                     }
                 }
             } else {
                 if ($tag == 'pre' || $tag == 'textarea') {
                     $raw_tag = $tag;
                 } else if ($tag == '/pre' || $tag == '/textarea') {
                     $raw_tag = false;
                 } else {
                     if ($raw_tag || $overriding) {
                         $strip = false;
                     } else {
                         $strip = true;
                         $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
                         $content = str_replace(' />', '/>', $content);
                     }
                 }
             }

             if ($strip) {
                 $content = $this->flhm_removeWhiteSpace($content);
             }

             $html .= $content;
         }
         return $html;
     }

     public function flhm_parseHTML($html)
     {
         $this->html = $this->flhm_minifyHTML($html);
         if ($this->flhm_info_comment) {
             $this->html .= "\n" . $this->flhm_bottomComment($html, $this->html);
         }
     }

     protected function flhm_removeWhiteSpace($str)
     {
         $str = str_replace("\t", ' ', $str);
         $str = str_replace("\n", '', $str);
         $str = str_replace("\r", '', $str);

         while (stristr($str, '  ')) {
             $str = str_replace('  ', ' ', $str);
         }
         return $str;
     }
 }

 function flhm_wp_html_compression_finish($html)
 {
     return new FLHM_HTML_Compression($html);
 }

 function flhm_wp_html_compression_start()
 {
     ob_start('flhm_wp_html_compression_finish');
 }

 // Don't minify HTML for
 // - excluded domain
 // - admin area
 // - cron jobs
 // - ajax calls
 $flhm_exclude_domain = '.mdev';

 if (
     false === strpos(site_url(), $flhm_exclude_domain) &&
     !is_user_logged_in() &&
     !wp_doing_cron() &&
     !wp_doing_ajax()
 ) {
     add_action('init', __NAMESPACE__ . '\\flhm_wp_html_compression_start', 1);
 }
