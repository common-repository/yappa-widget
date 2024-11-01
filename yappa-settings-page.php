<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

class YappaSettingsPage
{

  private $yappaWidget;

  public function __construct($yappaWidget)
  {

    add_action('admin_menu', array($this, 'adminMenu'));

    add_action('admin_print_styles', array($this, 'printStyles'));

    add_action('admin_print_scripts', array($this, 'printScripts'));

    add_action('admin_init', array($this, 'adminInit'));

    $this->yappaWidget = $yappaWidget;
  }

  public function adminMenu()
  {

    add_menu_page(
      'Yappa Config',
      'Yappa',
      'manage_options',
      'yappa_options',
      array(
        $this,
        'settings_page'
      )
    );
  }

  public function adminInit()
  {

    register_setting('yappa-settings', 'all_posts');
    register_setting('yappa-settings', 'all_pages');
  }

  // Enqueue the css
  public function printStyles()
  {

    if (isset($_GET["page"]) && $_GET["page"] == "yappa_options") {

      wp_register_style(
        'configurationPage',
        plugins_url('yappa-widget/css/configuration-page.css')
      );

      wp_enqueue_style('configurationPage');

      wp_enqueue_style('load-fa', 'https://use.fontawesome.com/releases/v5.6.3/css/all.css');
    }
  }

  // Enqueue the js
  public function printScripts()
  {

    if (isset($_GET["page"]) && $_GET["page"] == "yappa_options") {

      wp_register_script(
        'yappaScript',
        plugins_url('js/yappaScript.js', __FILE__),
        array('jquery'),
        '',
        true
      );

      wp_enqueue_script('yappaScript');
    }
  }

  public function settings_page()
  {

    ?>
  <div class="yappa-settings-wrapper">
    <h1>Yappa Widget</h1>
    <h3 class="title-register">You need to register your domain in yappa:</h1>

      <div class="spinner-container"><i id="spinner" class="fas fa-circle-notch fa-spin icono"></i></div>

      <form id="checkboxes-container" method="post" action="options.php" name="settings">

        <?php settings_fields('yappa-settings'); ?>
        <?php do_settings_sections('yappa-settings'); ?>

        <label>
          <input type="checkbox" class="setting-checkbox" name="all_posts" value="1" <?php checked(get_option('all_posts')) ?> />
          <span class="setting-label">Embed widget on all post</span>
        </label>

        <label>
          <input type="checkbox" class="setting-checkbox" name="all_pages" value="1" <?php checked(get_option('all_pages')) ?> />
          <span class="setting-label">Embed widget on all pages</span>
        </label>

        <?php submit_button(); ?>

        <h2>Embedding the widget manually</h2>

        <p>You can embedd the widget on any page or post using the following shortcode: <b>[yappa-widget]</b></p>

        <p>Just edit the page/post where you want to inject it, and paste that shortcode at the end of the rich text editor.</p>

      </form>

      <div id="put-yappa-comments-here" style="margin-top: 30px; ">
        <?= $this->yappaWidget->getEmbedCode() ?>
      </div>

      <iframe id="form-iframe" src="<?= $this->yappaWidget->getPublisherFormURL() ?>" width="500" height="700"></iframe>

  </div>

<?php

}
}
