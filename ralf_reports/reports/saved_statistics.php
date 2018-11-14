<?php 
global $wpdb;

$saved_stats_table = '<table cellpadding="0" cellspacing="0" class="saved-stats-table">';
$saved_stats_table .= '<thead><tr>'
                    . '<th>Article</th>'
                    . '<th>Count</th>'
                    . '<th></th>'
                    . '</tr></thead><tbody>';
                    
$saved_stats = $wpdb->get_results('
  SELECT article_id, COUNT(*) AS count
  FROM saved_reports
  GROUP BY article_id
  LIMIT 20', 'ARRAY_A');

$saved_stats_count = count($saved_stats);
for($i = 0; $i < $saved_stats_count; $i++){
  $saved_stats_table .= '<tr>';

  $saved_stats_table .= '<td><a href="' . get_permalink($saved_state[$i]['article_id']) . '">' . get_the_title($saved_stats[$i]['article_id']) . '</a></td>';
  $saved_stats_table .= '<td>' . $saved_stats[$i]['count'] . '</td>';

  $saved_stats_table .= '</tr>';
}

$saved_stats_table .= '</tbody></table>';

echo $saved_stats_table;
echo '<p><a href="' . esc_url(get_admin_url('', 'index.php?page=saved-statistics-submenu-page')) . '" class="button">View All Stats</a></p>';