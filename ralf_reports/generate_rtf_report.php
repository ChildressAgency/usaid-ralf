<?php
if ( ! defined( 'ABSPATH' ) ) exit;

//$report_ids = $report_ids_array
function get_report($report_ids){
  $rtf_report = '<h1>Report of Activities and Associated Impacts</h1>';

  $activities_report = new WP_Query(array(
    'post_type' => 'activities',
    'posts_per_page' => -1,
    'post__in' => $report_ids
  ));

  if($activities_report->have_posts()){
    while($activities_report->have_posts()){
      $activities_report->the_post();

      
    }
  } wp_reset_postdata();
}