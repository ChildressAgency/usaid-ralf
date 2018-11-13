<?php

global $wpdb;

$emailed_reports_table = '<table cellpadding="0" cellspacing="0" class="emailed-reports-table">';
$emailed_reports_table .= '<thead><tr>'
                        . '<th>Emailed Domains</th>'
                        . '<th>Activities / Impacts</th>'
                        . '<th>Email Date</th>'
                        . '<th></th>'
                        . '</tr></thead><tbody>';

$emailed_reports = $wpdb->get_results('
  SELECT * 
  FROM emailed_reports
  ORDER BY email_date DESC
  LIMIT 10', 'ARRAY_A');

$emailed_reports_count = count($emailed_reports);
for($i = 0; $i < $emailed_reports_count; $i++){
  $emailed_reports_table .= '<tr>';

  $emailed_reports_table .= '<td style="border-bottom:1px solid #000;">' . $emailed_reports[$i]['email_domains'] . '</td>';

  $report_ids = $emailed_reports[$i]['report_ids'];
  $reports_list = get_reports_list($report_ids);
  $emailed_reports_table .= '<td style="border-bottom:1px solid #000;">' . $reports_list . '</td>';

  $emailed_reports_table .= '<td style="border-bottom:1px solid #000;">' . $emailed_reports[$i]['email_date'] . '</td>';

  $emailed_reports_table .= '<td style="border-bottom:1px solid #000;"><a href="' . esc_url(home_Url('view-report/?report_ids=' . $report_ids)) . '" target="_blank">View Report</a></td>';

  $emailed_reports_table .= '</tr>';
}

$emailed_reports_table .= '</tbody></table>';

echo $emailed_reports_table;
echo '<p><a href="' . esc_url(get_admin_url('', 'index.php?page=emailed-reports-submenu-page')) . '" class="button">View Full List</a></p>';

function get_reports_list($reports){
  global $wpdb;
  $report_ids = explode(',', $reports);
  $article_titles = [];

  foreach($report_ids as $report_id){
    $report_title = $wpdb->get_var($wpdb->prepare("
      SELECT post_title
      FROM {$wpdb->prefix}posts
      WHERE ID = %d", $report_id));

    $report_title_list = '<li>' . $report_title . '</li>';
    $article_titles[] = $report_title_list;
  }

  $reports_list = '<ul class="reports-list">' . implode('', $article_titles) . '</ul>';
  return $reports_list;
}