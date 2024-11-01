<?php
/*
Plugin Name:  Yappa Widget
Plugin URI:   https://yappaapp.com/
Description:  Plugins that inserts the Yappa Widget into posts or pages
Version:      1.4.0
Author:       Yappa
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly
defined('ABSPATH') or die('No script kiddies please!');

include('yappa-settings-page.php');

class YappaWidget
{
  private $yappaGatewayHost;
  public function __construct()
  {
    $this->yappaGatewayHost = "https://comments.yappaapp.com";

    if (isset($_ENV['YAPPA_GATEWAY_HOST'])) {
      $this->yappaGatewayHost = $_ENV['YAPPA_GATEWAY_HOST'];
    }

    add_filter('the_content', array($this, 'theContent'), 99);

    add_shortcode('yappa-widget', array($this, 'shortcode'), 99);
  }

  public function shortcode($atts)
  {

    // we don't want the widget to be rendered on archive pages
    if (!is_singular()) {

      return '';
    }

    $allPosts = get_option('all_posts');
    $allPages = get_option('all_pages');

    if (!$allPages && get_post_type() == 'page' || !$allPosts && get_post_type() == 'post') {

      return $this->getEmbedCode();
    }

    return '';
  }


  public function theContent($content)
  {

    // we don't want the widget to be rendered on archive pages
    if (!is_singular()) {

      return $content;
    }

    $allPosts = get_option('all_posts');
    $allPages = get_option('all_pages');

    if ($allPosts || $allPages) {

      if (($allPosts && get_post_type() == 'post') || ($allPages && get_post_type() == 'page')) {

        return $content .= $this->getEmbedCode(true);
      }
    }

    return $content;
  }

  public function getEmbedCode()
  {
    $embedJSURL = $this->getEmbedJSURL();

    $post_title = get_the_title();
    $post_url = get_the_permalink();
    $post_id = get_the_ID();

    $code = "
      <div id='yappa-comments-frame' data-title='$post_title' data-url='$post_url' data-id='$post_id'></div>
      <script type='text/javascript' src='" . $embedJSURL . "'></script>
    ";

    return $code;
  }

  private function getEmbedJSURL()
  {
    return $this->yappaGatewayHost . '/embed/yappa-comments.js';
  }

  public function getPublisherFormURL()
  {
    $publisherDomain = parse_url(get_site_url());
    $publisherHost = $publisherDomain['host'];
    return $this->yappaGatewayHost . '/auth/publisher/form?domain=' . $publisherHost;
  }
}

$yappaWidget = new YappaWidget();
// Instances the settings page
new YappaSettingsPage($yappaWidget);
