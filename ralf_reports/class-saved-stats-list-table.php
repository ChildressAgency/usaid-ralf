<?php
if(!class_exists('WP_List_Table')){
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class saved_stats_list_table extends WP_List_Table{
  public function __construct(){
    parent::__construct([
      'singular' => __('Saved to Report Statistics', 'ralfreports'),
      'plural' => __('Saved to Report Statistics', 'ralfreports'),
      'ajax' => false
    ]);
  }

  public static function get_saved_stats($per_page = 25, $page_number = 1){

  }

  public static function saved_to_report_count(){
    global $wpdb;

    $report_count = $wpdb->get_var($wpdb->prepare("
      SELECT COUNT(*)
      FROM $wpdb->postmeta
      WHERE meta_key = %s
        AND meta_value > 0", 'saved_count'));

    return $report_count;
  }
}