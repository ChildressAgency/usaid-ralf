<?php 
$saved_stats_table = '<table cellpadding="0" cellspacing="0" class="saved-stats-table">';
$saved_stats_table .= '<thead><tr>'
                    . '<th>Article</th>'
                    . '<th>Count</th>'
                    . '</tr></thead><tbody>';
                    
$saved_stats = new WP_Query(array(
  'post_type' => array('activities', 'impacts'),
  'posts_per_page' => 20,
  'meta_key' => 'saved_count',
  'orderby' => 'meta_value',
  'order' => 'DESC'
));

if($saved_stats->have_posts()){
  while($saved_stats->have_posts()){
    $saved_stats->the_post();

    $saved_stats_table .= '<tr>'
                        . '<td>' . get_the_title() . '</td>'
                        . '<td>' . get_field('saved_count') . '</td>'
                        . '</tr>';
  }
} wp_reset_postdata();

$saved_stats_table .= '</tbody></table>';

echo $saved_stats_table;
echo '<p><a href="' . esc_url(get_admin_url('', 'index.php?page=saved-statistics-submenu-page')) . '" class="button">View All Stats</a></p>';