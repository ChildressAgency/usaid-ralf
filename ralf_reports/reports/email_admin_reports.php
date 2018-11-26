<?php
if(!isset($_GET['email_admin_reports'])){
  return;
}

$results_limit = get_fields('number_of_results', 'option');
$saved_to_report = get_field('saved_to_report', 'option');
$searched_terms = get_field('searched_terms', 'option');

if($_GET['email_admin_reports'] == 'weekly'){
  email_saved_to_report($saved_to_report['weekly_email_addresses']);
  email_searched_terms($searched_terms['weekly_email_addresses']);
}
elseif($_GET['email_admin_reports'] == 'monthly'){
  email_saved_to_report($saved_to_report['monthly_email_addresses']);
  email_searched_terms($searched_terms['monthly_email_addresses']);
}
else{
  return;
}

function email_saved_to_report($email_addresses){

}

function email_searched_terms($email_addresses){
  global $wpdb;

  $sql_select_from = "SELECT query AS searched_term, COUNT (*) AS searched_count, hits FROM {$wpdb->prefix}swp_log";
  $sql_select_from .= ' WHERE query NOT REGEXP "[()^;<>/\'\"!]"';

  $sql_group_by = ' GROUP BY query ORDER BY searched_count DESC LIMIT ' . $results_limit;

  $sql_time_period = ' AND tstamp >= DATE_ADD(NOW(), INTERVAL -90 DAY)';

  $all_time_results = get_searched_term_results($sql_select_from . $sql_group_by);
  $ninety_days_results = get_searched_term_results($sql_select_from . $sql_time_period . $sql_group_by);

  // create message table, one for all-time and one for 90-days
  $message = '<h3>Search Term History - All Time</h3>';
  $message .= create_results_table($all_time_results);

  $message .= '<h3>Search Term History - Last 90 Days';
  $message .= create_results_table($ninety_days_results);
}

function get_searched_term_results($sql){
  global $wpdb;

  $results = $wpdb->get_results($sql, 'ARRAY_A');

  $results_count = count($results);
  for($r = 0; $r < $results_count; $r++){
    $searched_term_link = '<a href="' . esc_url(add_query_arg('s', $results[$r]['searched_term'], home_url())) . '" target="_blank">' . $results[$r]['searched_term'] . '</a>';

    $results[$r]['searched_term'] = $searched_term_link;
  }

  return $results;
}

function create_results_table($results){
  $table = '<table cellpadding="1" cellspacing="0" style="width:100%;"><thead><tr>'
          . '<th>Search Term</th>'
          . '<th>Hits</th>'
          . '<th>Searched Count</th>'
          . '</tr></thead><tbody>';
  
  foreach($results as $search_term){
    $table .= '<tr>'
            . '<td>' . $search_term['searched_term'] . '</td>'
            . '<td>' . $search_term['hits'] . '</td>'
            . '<td>' . $search_term['searched_count'] . '</td>'
            . '</tr>';
  }

  $table .= '</tbody></table>';
  return $table;
}