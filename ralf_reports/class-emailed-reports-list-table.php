<?php
//https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
if(!class_exists('WP_List_Table')){
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class ralf_list_table extends WP_List_Table{
  public function __construct(){
    parent::__construct([
      'singular' => __('Emailed Report', 'ralfreports'),
      'plural' => __('Emailed Reports', 'ralfreports'),
      'ajax' => false
    ]);
  }

  public static function get_emailed_reports($per_page = 25, $page_number = 1){
    global $wpdb;

    $sql = 'SELECT * FROM emailed_reports';

    if(!empty($_REQUEST['orderby'])){
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : 'ASC';
    }

    $sql .= ' LIMIT ' . $per_page;
    $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

    $result = $wpdb->get_results($sql, 'ARRAY_A');

    $result_count = count($result);
    for($r = 0; $r < $result_count; $r++){
      $reports = $result[$r]['report_ids'];
      $report_ids = explode(',', $reports);

      $article_titles = [];
      foreach($report_ids as $report_id){
        $report_title = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM {$wpdb->prefix}posts WHERE ID = %d", $report_id));

        $report_title_list = '<li>' . $report_title . '</li>';

        $article_titles[] = $report_title_list;
      }
      $result[$r]['report_ids'] = '<ul style="margin-top:0; list-style:disc;">' . implode('', $article_titles) . '</ul>';
    }
    
    //var_dump($result);
    return $result;
  }

  public static function delete_emailed_report($id){
    global $wpdb;

    $wpdb->delete(
      'emailed_reports',
      ['ID' => $id],
      ['%d']
    );
  }

  public static function emailed_reports_count(){
    global $wpdb;

    $sql = 'SELECT COUNT(*) FROM emailed_reports';
    return $wpdb->get_var($sql);
  }

  public function no_items(){
    _e('No emailed reports were found.', 'ralfreports');
  }

  function column_name($item){
    $delete_nonce = wp_create_nonce('ralfreports_delete_emailed_report');
    $title = '<strong>' . $item['name'] . '</strong>';

    $actions = array('delete' =>
      sprintf(
        '<a href="?page=%s&action=%s&emailed_report=%s&_wpnonce=%s">Delete</a>', 
        esc_attr($_REQUEST['page']), 
        'delete', 
        absint($item['ID']), 
        $delete_nonce
      )
    );

    return $title . $this->row_actions($actions);
  }

  public function column_default($item, $column_name){
    switch($column_name){
      case 'email_domains':
      case 'report_ids':
      case 'email_date':
        return $item[$column_name];
      default:
        return print_r($item, true);
    }
  }

  function column_cb($item){
    return sprintf(
      '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
    );
  }

  function get_columns(){
    $columns = array(
      'cb' => '<input type="checkbox" />',
      'email_domains' => __('Emailed Domains', 'ralfreports'),
      'report_ids' => __('Activites / Impacts', 'ralfreports'),
      'email_date' => __('Email Date', 'ralfreports')
    );

    return $columns;
  }

  public function get_sortable_columns(){
    $sortable_columns = array(
      'email_domains' => array('email_domains', true),
      'report_ids' => array('report_ids', false),
      'email_date' => array('email_date', true)
    );

    return $sortable_columns;
  }

  public function get_bulk_actions(){
    $actions = array('bulk-delete' => 'Delete');

    return $actions;
  }

  public function prepare_items(){
    $this->_column_headers = $this->get_column_info();

    $this->process_bulk_action();

    $per_page = $this->get_items_per_page('emailed_reports_per_page', 25);
    $current_page = $this->get_pagenum();
    $total_items = self::emailed_reports_count();

    $this->set_pagination_args([
      'total_items' => $total_items,
      'per_page' => $per_page
    ]);

    $this->items = self::get_emailed_reports($per_page, $current_page);
  }

  public function process_bulk_action(){
    if('delete' == $this->current_action()){
      $nonce = esc_attr($_REQUEST['_wpnonce']);

      if(!wp_verify_nonce($nonce, 'ralf_delete_emailed_report')){
        die('Go get a life script kiddies');
      }
      else{
        self::delete_emailed_report(absint($_GET['emailed_report']));
        wp_redirect(esc_url(add_query_arg()));
        exit;
      }
    }

    if((isset($_POST['action'])
      && $_POST['action'] == 'bulk-delete') 
      || (isset($_POST['action2']) 
      && $_POST['action2'] == 'bulk-delete')){

      $delete_ids = esc_sql($_POST['bulk-delete']);

      foreach($delete_ids as $id){
        self::delete_emailed_report($id);
      }

      wp_redirect(esc_url(add_query_arg()));
      exit;
    }
  }
}