<?php
/*
  Plugin Name: RALF Reports
  Description: Creates and manages the report for selected RALF documents
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 1.0
  Text Domain: ralfreports
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class ralf_report{
  public function __construct(){
    add_shortcode('ralf_report', array($this, 'ralf_report'));
    add_shortcode('email_form', array($this, 'email_rtf_form'));

    add_action('wp_enqueue_scripts', array($this, 'scripts'));

    add_action('wp_ajax_nopriv_send_rtf_report', array($this, 'send_rtf_report'));
    add_action('wp_ajax_send_rtf_report', array($this, 'send_rtf_report'));
  }

  function ralf_report(){
    include('view-report.php');
  }

  function scripts(){
    wp_enqueue_style('ralf-report', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('ralf-report', plugin_dir_url(__FILE__) . 'js/scripts.js', array('jquery'), null, true);

    wp_localize_script('ralf-report', 'ralf_settings', array(
      'ralf_ajaxurl' => admin_url('admin-ajax.php'),
      'send_label' => __('Email Report', 'ralfreports'),
      'error' => __('Sorry, something went wrong. Please try again.', 'ralfreports')
    ));
  }

  public function email_rtf_form($atts){
    //convert activities_ids to string
    $report_ids = implode(',', $atts['activity_ids']);

    $nonce = wp_create_nonce('email_rtf_report_' . $report_ids);
    $form_content = '<div class="email-report">
                      <h3>Email this report</h3>
                      <div class="form-group">
                        <input type="text" id="email-addresses" name="email-addresses" class="form-control" placeholder="' . __('Enter a comma-separated list of email addresses.', 'ralfreports') . '" />
                      </div>
                      <button class="btn-main btn-report send-email" data-nonce="' . $nonce . '" data-report_ids="' . $report_ids . '">' . __('Send Email', 'ralfreports') . '</button>
                      <p class="email-response"></p>
                    </div>';

    echo $form_content;
  }

  function send_rtf_report(){
    $report_ids = $_POST['report_ids'];
    $email_addresses = $_POST['email-addresses'];
    $report_ids_array = explode(',', $report_ids);
    //verify nonce
    if(check_ajax_referer('email_rtf_report_' . $report_ids, 'nonce', false) == false){
      wp_send_json_error();
    }

    //include('class_rtf.php');
    //include('generate_rtf_report.php');

    $rtf_report = $this->get_report($report_ids_array);
/*
    $rtf = new rtf('rtf_config.php');
    $rtf->setPaperSize(5);
    $rtf->setPaperOrientation(1);
    $rtf->setDefaultFontFace(0);
    $rtf->setDefaultFontSize(24);
    $rtf->setAuthor("jcampbell");
    $rtf->setOperator("jcampbell@childressagency.com");
    $rtf->setTitle("RALF Impact Report");
    $rtf->addColour("#000000");
    //$rtf->addText($_POST['text']);
    $rtf->addText($rtf_report);
    $rtf->getDocument();
*/

    include('HtmlToRtf.php');
    //$htmlToRtfConverter = new HtmlToRtf\HtmlToRtf($rtf_report);
    //$htmlToRtfConverter->getRTFFile();

    $upload_dir = wp_upload_dir();
    $upload_dir_base = $upload_dir['basedir'];
    $ralf_reports_folder = $upload_dir_base . '/ralf_reports/';
    $ralf_report_name = $ralf_reports_folder . 'ralf_report_' . date("mdY-His") . '.rtf';

    //file_put_contents($ralf_report_name, print_r($htmlToRtfConverter, true));

    //$ralf_report_file = fopen($ralf_report_name, 'w');
    //$ralf_report_contents = print_r($rtf, true);
    //$ralf_report_contents = $rtf;
    //fwrite($ralf_report_file, $ralf_report_contents);
    //fwrite($ralf_report_file, $rtf);
    //fclose($ralf_report_file);

//$rtf = $this->get_report($report_ids_array);

    $to = $email_addresses;
    $subject = 'Your RALF Impact Report';
    //$headers = 'From: USAID RALF <jcampbell@childressagency.com>';
    $headers = '';
    $message = 'Your RALF Impact Report is attached to this email. Your chosen Activities are listed below:' . "\r\n\r\n";

    foreach($report_ids_array as $report_id){
      $message .= ' - ' . get_the_title($report_id) . "\r\n";
    }

    $message .= "\r\n" . 'Here is a link back to your report: ' . esc_url(add_query_arg('report_ids', $report_ids, home_url('view-report')));
    $message .= "\r\n" . $ralf_report_name;

    //$result = wp_mail($to, $subject, $message, $headers, $ralf_report_name);
    //$result = wp_mail($to, $subject, $rtf, $headers);

    if($result == true){
      wp_send_json_success(__('Report email sent!', 'ralfreports'));
    }
    else{
      wp_send_json_error();
    }
  }

  function get_report($report_ids){
    $rtf_report = '<h1>Report of Activities and Associated Impacts</h1>';

    $activities_report = new WP_Query(array(
      'post_type' => 'activities',
      'posts_per_page' => -1,
      'post__in' => $report_ids
    ));
    //$rtf_report = print_r($activities_report, true);

    if($activities_report->have_posts()){
      while($activities_report->have_posts()){
        $activities_report->the_post();

        $rtf_report .= '<h2>' . get_the_title() . '</h2>';
        $rtf_report .= get_the_content();
        $rtf_report .= '<h3>CONDITIONS</h3>';
        $rtf_report .= get_field('conditions');

        $impact_ids = get_field('related_impacts', false, false);
        if(!empty($impact_ids)){
          $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids);
          $rtf_report .= '<h3>IMPACT BY SECTOR</h3>';

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
}

new ralf_report;