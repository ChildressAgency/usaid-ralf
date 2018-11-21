<?php
// Exit if accessed directly
if (!defined('ABSPATH')){ exit; }

<header class="page-header">
  <h1><?php _e('Report of Activities and Associated Impacts', 'ralfreports'); ?></h1>
</header>

<?php
$activities_ids = [];
if(isset($_GET['report_ids'])){
  $report_ids = explode(',', $_GET['report_ids']);

  $activities_ids = array_map(
    function($value){ return (int)$value; },
    $report_ids
  );
}
else if(get_query_var('report_id')){
  $report_id = get_query_var('report_id');
  global $wpdb;

  $report_ids_field = $wpdb->get_var($wpdb->prepare("
    SELECT report_ids
    FROM emailed_reports
    WHERE ID = %d", $report_id));

  $report_ids = explode(',', $report_ids_field);

  $activities_ids = array_map(
    function($value){ return (int)$value; },
    $report_ids
  );
}
else if(isset($_COOKIE['STYXKEY_report_ids'])){
  $report_ids_cookie = $_COOKIE['STYXKEY_report_ids'];

  $report_ids = explode(',', $report_ids_cookie);

  $activities_ids = array_map(
    function($value){ return (int)$value; },
    $report_ids
  );
}

if($activities_ids){
  $activities_report = new WP_Query(array(
    'post_type' => array('activities', 'impacts'),
    'posts_per_page' => -1,
    'post__in' => $activities_ids,
    'orderby' => 'post_type'
  ));

  if($activities_report->have_posts()){
    while($activities_report->have_posts()){
      $activities_report->the_post();
      require 'report-loop.php';
    }
  } wp_reset_postdata();

  global $shortcode_tags;
  return call_user_func($shortcode_tags['email_form'], array('activity_ids' => $activities_ids));
}
else{
  echo '<p>' . __('Sorry, your report could not be found.', 'ralfreports') . '</p>';
}