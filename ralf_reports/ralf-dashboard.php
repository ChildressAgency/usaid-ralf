<?php
if(!class_exists('ralf_list_table')){
  require_once 'class-emailed-reports-list-table.php';
}

class ralf_dashboard{
  static $instance;
  public $emailed_reports_list;
  public $saved_stats_list;

  public function __construct(){
    add_action('wp_dashboard_setup', array($this, 'ralf_dashboard_setup'));

    add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
    add_action('admin_menu', array($this, 'dashboard_submenus'));
  }

  function ralf_dashboard_setup(){
    wp_add_dashboard_widget(
      'emailed-reports',
      'Emailed Reports',
      array($this, 'show_emailed_reports_dashboard')
    );

    wp_add_dashboard_widget(
      'saved-to-report-statistics',
      'Saved to Report Statistics',
      array($this, 'show_saved_statistics_dashboard')
    );
  }

  public function show_emailed_reports_dashboard(){
    require_once 'reports/emailed_reports.php';
  }

  public function show_saved_statistics_dashboard(){
    require_once 'reports/saved_statistics.php';
  }

  public static function set_screen($status, $option, $value){
    return $value;
  }

  public function dashboard_submenus(){
    $emailed_reports_submenu = add_submenu_page(
      'index.php',
      'Emailed Reports',
      'Emailed Reports',
      'manage_options',
      'emailed-reports-submenu-page',
      array($this, 'show_emailed_reports_submenu')
    );

    add_action("load-$emailed_reports_submenu", array($this, 'emailed_reports_screen_option'));

    $saved_stats_submenu = add_submenu_page(
      'index.php',
      'Saved to Report Statistics',
      'Saved Stats',
      'manage_options',
      'saved-statistics-submenu-page',
      array($this, 'show_saved_stats_submenu')
    );

    add_action("load-$saved_stats_submenu", array($this, 'saved_stats_screen_option'));
  }

  public function emailed_reports_screen_option(){
    $option = 'per_page';
    $args = array(
      'label' => 'Emailed Reports Per Page',
      'default' => 25,
      'option' => 'emailed_reports_per_page'
    );

    add_screen_option($option, $args);

    $this->emailed_reports_list = new emailed_reports_list_table();
  }

  public function saved_stats_screen_option(){
    $option = 'per_page';
    $args = array(
      'label' => 'Saved Stats Per Page',
      'default' => 25,
      'option' => 'saved_stats_per_page'
    );

    add_screen_option($option, $args);

    $this->saved_stats_list = new saved_stats_list_table();
  }

  public function show_emailed_reports_submenu(){
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

  public function show_saved_stats_submenu(){
    ?>
    <div class="wrap">
      <h2>Saved to Report Statistics</h2>

      <div id="poststuff">
        <div id="post-body" class="metabox-holder">
          <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
              <form method="post">
                <?php
                  $this->saved_stats_list->prepare_items();
                  $this->saved_stats_list->display();
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
  ralf_dashboard::get_instance();
});