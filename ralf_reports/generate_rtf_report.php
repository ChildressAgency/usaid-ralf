<?php
// Exit if accessed directly
if (!defined('ABSPATH')){ exit; }

//$report_ids = $report_ids_array
function get_report($report_ids){
  $rtf_report = '<h1>' . __('Report of Activities and Associated Impacts', 'ralfreports') . '</h1>';

  $activities_report = new WP_Query(array(
    'post_type' => 'activities',
    'posts_per_page' => -1,
    'post__in' => $report_ids
  ));

  if($activities_report->have_posts()){
    while($activities_report->have_posts()){
      $activities_report->the_post();

      $rtf_report .= '<h2>' . get_the_title() . '</h2>';
      $rtf_report .= get_the_content();
      $rtf_report .= '<h3>' . __('CONDITIONS', 'ralfreports') . '</h3>';
      $rtf_report .= get_the_field('conditions');

      $impact_ids = get_field('related_impacts', false, false);
      if(!empty($impact_ids)){
        $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids);
        $rtf_report .= '<h3>' . __('IMPACT BY SECTOR', 'ralfreports') . '</h3>';

        foreach($impacts_by_sector as $sector){
          foreach($sector['impacts'] as $impact){
            $rtf_report .= '<h4>' . $impact->impact_title . '</h4>';
            $rtf_report .= $impact->impact_description;
          }
        }
      }

    }
  } wp_reset_postdata();

  return $rtf_report;
}