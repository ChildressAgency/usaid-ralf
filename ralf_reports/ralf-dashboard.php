<?php
if(!class_exists('emailed_reports_list_table')){
  require_once 'class-emailed-reports-list-table.php';
}
if(!class_exists('saved_stats_list_table')){
  require_once 'class-saved-stats-list-table.php';
}

class ralf_dashboard{
  static $instance;
  public $emailed_reports_list;
  public $saved_stats_list;

  public function __construct(){
    add_action('wp_dashboard_setup', array($this, 'ralf_dashboard_setup'));

    add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
    add_action('admin_menu', array($this, 'dashboard_submenus'));

    add_action('load-post.php', array($this, 'init_metabox'));
    add_action('load-post-new.php', array($this, 'init_metabox'));

    add_filter('manage_activities_posts_columns', array($this, 'set_saved_count_column'));
    add_filter('manage_impacts_posts_columns', array($this, 'set_saved_count_column'));
    add_action('manage_activities_posts_custom_column', array($this, 'activities_saved_count_column_values', 10, 2));
    add_action('manage_impacts_posts_custom_column', array($this, 'impacts_saved_count_column_values', 10, 2));
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

  public function init_metabox(){
    add_action('add_meta_boxes', array($this, 'add_saved_count_metabox'));
  }

  public function add_saved_count_metabox($post_type){
    $post_types = array('activities', 'impacts');
    if(in_array($post_type, $post_types)){
      add_meta_box(
        'save-count',
        __('Number of Times Saved to Report', 'ralfreports'),
        array($this, 'show_saved_count'),
        $post_type,
        'side'
      );
    }
  }

  public function show_saved_count($post){
    global $wpdb;
    $article_id = $post->ID;

    $saved_count = get_saved_count($article_id);

    echo '<p>' . $saved_count . '</p>';
  }

  public function get_saved_count($article_id){
    $saved_count = $wpdb->get_var($wpdb->prepare("
      SELECT COUNT(*) AS count
      FROM $wpdb->postmeta,
      WHERE article_id = %d", $article_id));

    return $saved_count;
  }

  public function set_saved_count_column($columns){
    $columns['saved_count'] = __('Number of Times Saved to Report', 'ralfreports');
    return $columns;
  }

  public function activities_saved_count_column_values($column, $post_id){
    if($column == 'saved_count'){
      $saved_count = get_saved_count($post_id);
      echo $saved_count;
    }
  }

  public function impacts_saved_count_column_values($column, $post_id){
    if($column == 'saved_count'){
      $saved_count = get_saved_count($post_id);
      echo $saved_count;
    }
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