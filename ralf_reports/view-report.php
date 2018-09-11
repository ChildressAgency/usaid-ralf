<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; ?>

<header class="page-header">
  <h1>Report of Activities and Associated Impacts</h1>
</header>

<?php
if(isset($_COOKIE['STYXKEY_report_ids'])){
  $report_ids_cookie = $_COOKIE['STYXKEY_report_ids'];

  $report_ids = explode(',', $report_ids_cookie);

  $activities_ids = array_map(
    function($value){ return (int)$value; },
    $report_ids
  );

  $activities_report = new WP_Query(array(
    'post_type' => 'activities',
    'posts_per_page' => -1,
    'post__in' => $activities_ids
  ));

  if($activities_report->have_posts()){
    while($activities_report->have_posts()){
      $activities_report->the_post();
      require_once 'report-loop.php';
    }
  } wp_reset_postdata();

  echo do_shortcode('[email_form]');
}
else{
  echo '<p>You haven\'t saved any Activities to report.</p>';
}