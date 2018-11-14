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
    global $wpdb;

    $sql = 'SELECT article_id, COUNT(*) AS count FROM saved_reports GROUP BY article_id';

    if(!empty($_REQUEST['orderby'])){
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }

    $sql .= ' LIMIT ' . $per_page;
    $sql .= ' OFFSET ' . ($page_number -1) * $per_page;

    $result = $wpdb->get_results($sql, 'ARRAY_A');

    $result_count = count($result);
    for($r = 0; $r < $result_count; $r++){
      $article_title = $wpdb->get_var($wpdb->prepare("
      SELECT post_title
      FROM $wpdb->posts
      WHERE ID = %d", $result[$r]['article_id']));
    }

    return $result;
  }

  public static function saved_to_report_count(){
    global $wpdb;

    $report_count = $wpdb->get_var("
      SELECT COUNT(DISTINCT('article_id'))
      FROM saved_reports");

    return $report_count;
  }

  public function no_items(){
    _e('No saved reports were found.', 'ralfreports');
  }

  function column_name($item){
    $title = '<strong>' . $item['name'] . '</strong>';

    return $title
  }

  public function column_default($item, $column_name){
    switch($column_name){
      case 'article_name':
      case 'saved_count':
        return $item[$column_name];
      default:
        return print_r($item, true);
    }
  }

  function get_columns(){
    $columns = array(
      'article_name' => __('Article Name', 'ralfreports'),
      'saved_count' => __('saved_count')
    ); 

    return $columns;
  }

  public function get_sortable_columns(){
    $sortable_columns = array(
      'article_name' => array('article_name', true);
      'saved_count' => array('saved_counter', true);
    );

    return $sortable_columns;
  }

  public function prepare_items(){
    $this->_column_headers = $this->get_column_info();

    $per_page = $this->get_items_per_page('saved_stats_per_page', 25);
    $current_page = $this->get_pagenum();
    $total_items = $self::saved_to_report_count();

    $this->set_pagination_args([
      'total_items' => $total_items,
      'per_page' => $per_page
    ]);

    $this->items = self::get_saved_stats($per_page, $current_page);
  }
}