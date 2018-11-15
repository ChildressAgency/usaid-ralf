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

    $sql = 'SELECT article_id, COUNT(*) AS saved_count FROM saved_reports';

    if(!empty($_REQUEST['time_period'])){
      if($_REQUEST['time_period'] == 'ninety_days'){
        $sql .= ' WHERE saved_date >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
      }
    }
    
    $sql .= ' GROUP BY article_id';

    if(!empty($_REQUEST['orderby'])){
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
    }

    $sql .= ' LIMIT ' . $per_page;
    $sql .= ' OFFSET ' . ($page_number -1) * $per_page;

    $result = $wpdb->get_results($sql, 'ARRAY_A');

    //change article id to title and link - still called article_id in array
    $result_count = count($result);
    for($r = 0; $r < $result_count; $r++){
      $article_title = get_the_title($result[$r]['article_id']);
      $article_link = get_permalink($result[$r]['article_id']);

      $result[$r]['article_id'] = '<a href="' . $article_link . '" target="_blank">' . $article_title . '</a>';
    }

    return $result;
  }

  public static function saved_to_report_count(){
    global $wpdb;

    $report_count = $wpdb->get_results("
      SELECT article_id
      FROM saved_reports
      GROUP BY article_id", 'ARRAY_N');

    return count($report_count);
  }

  public function no_items(){
    _e('No saved reports were found.', 'ralfreports');
  }

  function column_name($item){
    $title = '<strong>' . $item['name'] . '</strong>';

    return $title;
  }

  public function column_default($item, $column_name){
    switch($column_name){
      case 'article_id':
      case 'saved_count':
        return $item[$column_name];
      default:
        return print_r($item, true);
    }
  }

  function get_columns(){
    $columns = array(
      'article_id' => __('Article Name', 'ralfreports'),
      'saved_count' => __('Number of Times Saved to Report', 'ralfreports')
    ); 

    return $columns;
  }

  public function get_sortable_columns(){
    $sortable_columns = array(
      'article_id' => array('article_id', true),
      'saved_count' => array('saved_count', true)
    );

    return $sortable_columns;
  }

  protected function get_views(){
    $status_links = array(
      'all' => __('<a href="' . esc_url(get_admin_url('', 'index.php?page=saved-statistics-submenu-page&time_period=all')) . '">All</a>', 'ralfreports'),
      'ninety_days' => __('<a href="' . esc_url(get_admin_url('', 'index.php?page=saved-statistics-submenu-page&time_period=ninety_days')) . '">Last 90 Days</a>', 'ralfreports')
    );

    return $status_links;
  }

  public function prepare_items(){
    $this->_column_headers = $this->get_column_info();

    $per_page = $this->get_items_per_page('saved_stats_per_page', 25);
    $current_page = $this->get_pagenum();
    $total_items = self::saved_to_report_count();

    $this->set_pagination_args([
      'total_items' => $total_items,
      'per_page' => $per_page
    ]);

    $this->items = self::get_saved_stats($per_page, $current_page);
  }
}