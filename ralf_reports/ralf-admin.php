<?php
/*
add_action('admin_menu', 'register_email_report_submenu')
function register_email_report_submenu(){
  add_submenu_page(
    'index.php',
    'Emailed Reports',
    'Emailed Reports',
    'edit_posts',
    'emailed-reports-submenu-page',
    array($this, 'show_emailed_reports')
  );
}

function show_emailed_reports(){
  if(!class_exists('ralf_list_table')){
    require_once 'class-emailed-reports-list-table.php';
  }
}
*/

if(!class_exists('ralf_list_table')){
  require_once 'class-emailed-reports-list-table.php';
}

class ralf_emailed_reports{
  static $instance;
  public $emailed_reports_list;

  public function __construct(){
    add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
    add_action('admin_menu', array($this, 'emailed_reports_submenu'));
  }

  public static function set_screen($status, $option, $value){
    return $value;
  }

  public function emailed_reports_submenu(){
    $hook = add_submenu_page(
      'index.php',
      'Emailed Reports',
      'Emailed Reports',
      'manage_options',
      'emailed-reports-submenu-page',
      array($this, 'show_emailed_reports')
    );

    add_action("load-$hook", array($this, 'screen_option'));
  }

  public function screen_option(){
    $option = 'per_page';
    $args = array(
      'label' => 'Emailed Reports',
      'default' => 25,
      'option' => 'emailed_reports_per_page'
    );

    add_screen_option($option, $args);

    $this->emailed_reports_list = new ralf_list_table();
  }

  public function show_emailed_reports(){
    ?>
    <div class="wrap">
      <h2>Emailed Reports</h2>

      <div id="poststuff">
        <div id="post-body" class="metabox-holder">
          <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
              <form method="post">
                <?php
                  $this->emailed_reports_list->prepare_items();
                  $this->emailed_reports_list->display();
                ?>
              </form>
            </div>
          </div>
        </div>
        <br class="clear" />
      </div>
    </div>
    <?php
  }

  public static function get_instance(){
    if(!isset(self::$instance)){
      self::$instance = new self();
    }

    return self::$instance;
  }
}

add_action('plugins_loaded', function(){
  ralf_emailed_reports::get_instance();
});