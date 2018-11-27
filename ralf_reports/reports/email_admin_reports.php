<?php
/*
  Cronjobs
    Weekly report:
      0 0 * * 0 /usr/bin/wget https://cycat25.com/?email_admin_reports=weekly

    Monthly Report
     0 0 1 * * /usr/bin/wget https://cycat25.com/?email_admin_reports=monthly
*/

if(!isset($_GET['email_admin_reports'])){
  return;
}

$results_limit = get_field('number_of_results', 'option');
$saved_to_report = get_field('saved_to_report', 'option');
$searched_terms = get_field('searched_terms', 'option');

if($_GET['email_admin_reports'] == 'weekly'){
  email_saved_to_report($saved_to_report['weekly_email_addresses'], $results_limit);
  email_searched_terms($searched_terms['weekly_email_addresses'], $results_limit);
}
elseif($_GET['email_admin_reports'] == 'monthly'){
  email_saved_to_report($saved_to_report['monthly_email_addresses'], $results_limit);
  email_searched_terms($searched_terms['monthly_email_addresses'], $results_limit);
}
else{
  return;
}

function email_saved_to_report($email_addresses, $results_limit){
  if(!empty($email_addresses)){
    $sql_select_from = 'SELECT article_id, COUNT(*) AS saved_count FROM saved_reports';
    $sql_where = ' WHERE saved_date >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
    $sql_group_by = ' GROUP BY article_id ORDER BY saved_count DESC LIMIT ' . $results_limit;

    $all_time_results = get_saved_to_report_results($sql_select_from . $sql_group_by);
    $ninety_days_results = get_saved_to_report_results($sql_select_from . $sql_where . $sql_group_by);

    //create message table, one for all-time and on fro 90-days
    $message = '<h3>' . __('Articles Saved to Report History - All Time', 'ralfreports') . '</h3>';
    $message .= create_saved_to_report_table($all_time_results);

    $message .= '<br /><h3>' . __('Articles Saved to Report History - Last 90 Days', 'ralfreports') . '</h3>';
    $message .= create_saved_to_report_table($ninety_days_results);

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $subject = sprintf(__("Articles Saved to Report History - %s Report", 'ralfreports'), ucfirst($_GET['email_admin_reports']));

    //send mail to each email address separately
    foreach($email_addresses as $email_address){
      wp_mail($email_address['email_address'], $subject, $message, $headers);
      //echo $message;
    }
  }
}

function email_searched_terms($email_addresses, $results_limit){
  if(!empty($email_addresses)){
    global $wpdb;

    $sql_select_from = "SELECT query AS searched_term, COUNT(*) AS searched_count, hits FROM {$wpdb->prefix}swp_log";
    $sql_select_from .= ' WHERE query NOT REGEXP "[()^;<>/\'\"!]"';

    $sql_group_by = ' GROUP BY query ORDER BY searched_count DESC LIMIT ' . $results_limit;

    $sql_time_period = ' AND tstamp >= DATE_ADD(NOW(), INTERVAL -90 DAY)';

    $all_time_results = get_searched_term_results($sql_select_from . $sql_group_by);
    $ninety_days_results = get_searched_term_results($sql_select_from . $sql_time_period . $sql_group_by);

    // create message table, one for all-time and one for 90-days
    $message = '<h3>' . __('Search Term History - All Time', 'ralfreports') . '</h3>';
    $message .= create_search_term_results_table($all_time_results);

    $message .= '<br /><h3>' . __('Search Term History - Last 90 Days', 'ralfreports') . '</h3>';
    $message .= create_search_term_results_table($ninety_days_results);

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $subject = sprintf(__("Search History - %s Report", 'ralfreports'), ucfirst($_GET['email_admin_reports']));

    //send mail to each email address separately
    foreach($email_addresses as $email_address){
      wp_mail($email_address['email_address'], $subject, $message, $headers);
    }
  }
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

function get_saved_to_report_results($sql){
  global $wpdb;

  $results = $wpdb->get_results($sql, 'ARRAY_A');

  $results_count = count($results);
  for($r = 0; $r < $results_count; $r++){
    $article_title = get_the_title($results[$r]['article_id']);
    //$article_link = esc_url(get_permalink($results[$r]['article_id']));
    $article_post_type = get_post_type($results[$r]['article_id']);
    $article_link = esc_url(home_url($article_post_type . '/' . sanitize_title($article_title)));

    $results[$r]['article_id'] = '<a href="' . $article_link . '" target="_blank">' . $article_title . '</a>';
  }

  return $results;
}

function create_search_term_results_table($results){
  $table = '<table cellpadding="1" cellspacing="0" style="text-align:left; width:100%;"><thead><tr>'
          . '<th>' . __('Search Term', 'ralfreports') . '</th>'
          . '<th>' . __('Hits', 'ralfreports') . '</th>'
          . '<th>' . __('Searched Count', 'ralfreports') . '</th>'
          . '</tr></thead><tbody>';
  
  foreach($results as $search_term){
    $table .= '<tr>'
            . '<td style="border-bottom:1px solid #000;">' . $search_term['searched_term'] . '</td>'
            . '<td style="border-bottom:1px solid #000;">' . $search_term['hits'] . '</td>'
            . '<td style="border-bottom:1px solid #000;">' . $search_term['searched_count'] . '</td>'
            . '</tr>';
  }

  $table .= '</tbody></table>';
  return $table;
}

function create_saved_to_report_table($results){
  $table = '<table cellpadding="1" cellspacing="0" style="text-align:left; width:100%;"><thead><tr>'
          . '<th>' . __('Article', 'ralfreports') . '</th>'
          . '<th>' . __('Saved Count', 'ralfreports') . '</th>'
          . '</tr></thead><tbody>';
  
  foreach($results as $article){
    $table .= '<tr>'
            . '<td style="border-bottom:1px solid #000;">' . $article['article_id'] . '</td>'
            . '<td style="border-bottom:1px solid #000;">' . $article['saved_count'] . '</td>'
            . '</tr>';
  }

  $table .= '</tbody></table>';
  return $table;
}