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
if (!defined('ABSPATH')){ exit; }

class ralf_report{
  public function __construct(){
    add_action('init', array($this, 'ralf_report_load_textdomain'));
    add_action('plugins_loaded', array($this, 'process_admin_tasks'));

    add_shortcode('ralf_report', array($this, 'ralf_report'));
    add_shortcode('email_form', array($this, 'email_rtf_form'));
    add_shortcode('report_button', array($this, 'report_button_container'));

    add_action('wp_enqueue_scripts', array($this, 'scripts'));
    add_action('acf/init', array($this, 'create_acf_field_groups'));


    add_action('wp_ajax_nopriv_send_rtf_report', array($this, 'send_rtf_report'));
    add_action('wp_ajax_send_rtf_report', array($this, 'send_rtf_report'));
    add_action('wp_ajax_nopriv_record_report_save', array($this, 'record_report_save'));
    add_action('wp_ajax_record_report_save', array($this, 'record_report_save'));

    //add_filter('searchwp_query_results', array($this, 'log_search_results'), 10, 2);
  }

  function ralf_report_load_textdomain(){
    load_plugin_textdomain('ralfreports', false, basename(dirname(__FILE__)) . '/languages');
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
      'error' => __('Sorry, something went wrong. Please try again.', 'ralfreports'),
      'save_to_report_label' => __('Save To Report', 'ralfreports'),
      'remove_from_report_label' => __('Remove From Report', 'ralfreports'),
      'added_to_report_label' => __('Added to report!', 'ralfreports'),
      'removed_from_report_label' => __('Removed from report', 'ralfreports'),
      'valid_email_address_error' => __('Please enter only valid email addresses.', 'ralfreports')
    ));
  }

  public function email_rtf_form($atts){
    //convert activities_ids to string
    $report_ids = implode(',', $atts['activity_ids']);

    $nonce = wp_create_nonce('email_rtf_report_' . $report_ids);
    $form_content = '<div class="email-report">
                      <h3>' . __('Email this report', 'ralfreports') . '</h3>
                      <div class="form-group">
                        <input type="text" required id="email-addresses" name="email-addresses" class="form-control" placeholder="' . __('Enter a comma-separated list of email addresses.', 'ralfreports') . '" />
                      </div>
                      <button class="btn-main btn-report send-email" data-nonce="' . $nonce . '" data-report_ids="' . $report_ids . '">' . __('Send Email', 'ralfreports') . '</button>
                      <p class="email-response"></p>
                    </div>';

    echo $form_content;
  }

  function send_rtf_report(){
    $report_ids = $_POST['report_ids'];

    //sanitize email addresses
    $entered_email_addresses = $_POST['email-addresses'];
    $email_addresses = $this->sanitize_email_addresses($entered_email_addresses);

    //log emailed reports
    $report_url_id = $this->log_email_reports($email_addresses, $report_ids);

    //put report_ids string into array
    $report_ids_array = explode(',', $report_ids);

    //verify nonce
    if(check_ajax_referer('email_rtf_report_' . $report_ids, 'nonce', false) == false){
      wp_send_json_error();
    }

    //generate the html for the report 
    $html_report = $this->get_report($report_ids_array);

    //using vsword to convert the html into a docx
    require_once 'vsWord/VsWord.php';
    VsWord::autoLoad();

    $doc = new VsWord();
    $parser = new HtmlParser($doc);
    $parser->parse($html_report);

    //create the filename and path to save to, and where mailer will find the attachment
    //todo: delete the file after emailing? -check with client
    $upload_dir = wp_upload_dir();
    $upload_dir_base = $upload_dir['basedir'];
    $ralf_reports_folder = $upload_dir_base . '/ralf_reports/';
    $ralf_report_name = $ralf_reports_folder . 'ralf_report_' . date("mdY-His") . '.docx';

    $doc->saveAs($ralf_report_name);

    //create the email variables
    $to = $email_addresses;
    $subject = __('Your RALF Impact Report', 'ralfreports');
    //$headers = 'From: USAID RALF <jcampbell@childressagency.com>';
    $headers = '';
    $message = __('Your RALF Impact Report is attached to this email. Your chosen Activities are listed below:', 'ralfreports') . "\r\n\r\n";

    //show the title of each article in the message body
    foreach($report_ids_array as $report_id){
      $message .= ' - ' . get_the_title($report_id) . "\r\n";
    }

    //link to the report using querystrings with the ids
    $message .= "\r\n" . __('Here is a link back to your report: ', 'ralfreports') . esc_url(home_url('view-report/' . $report_url_id));
    //$message .= "\r\n" . $ralf_report_name;

    //send the email with attachment
    $result = wp_mail($to, $subject, $message, $headers, $ralf_report_name);

    //reply to the webpage
    if($result == true){
      wp_send_json_success(__('Report email sent!', 'ralfreports'));
    }
    else{
      wp_send_json_error();
    }
  }

  function get_report($report_ids){
    $rtf_report = '<h1>' . __('Report of Activities and Associated Impacts', 'ralfreports') . '</h1>';

    $activities_report = new WP_Query(array(
      'post_type' => array('activities', 'impacts', 'resources'),
      'posts_per_page' => -1,
      'post__in' => $report_ids,
      'orderby' => 'post_type',
      'order' => 'DESC'
    ));
    //$rtf_report = print_r($activities_report, true);

    if($activities_report->have_posts()){
      while($activities_report->have_posts()){
        $activities_report->the_post();

        $rtf_report .= '<h2>' . get_the_title() . '</h2>';
        $rtf_report .= '<p>' . get_the_content() . '</p>';
        
        $conditions = get_field('conditions');
        if($conditions){
          $rtf_report .= '<h3>' . __('CONDITIONS', 'ralfreports') . '</h3>';
          $rtf_report .= str_replace("& ", "and ", get_field('conditions'));
        }

        $impact_ids = get_field('related_impacts', false, false);
        if(!empty($impact_ids)){
          $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids);
          $rtf_report .= '<h3>' . __('IMPACT BY SECTOR', 'ralfreports') . '</h3>';

          foreach($impacts_by_sector as $sector){
            foreach($sector['impacts'] as $impact){
              $rtf_report .= '<h4>' . str_replace("& ", "and ", $impact->impact_title) . '</h4>';
              $rtf_report .= '<p>' . str_replace("& ", "and ", $impact->impact_description) . '</p>';
            }
          }
        }

      }
    } wp_reset_postdata();

    return $rtf_report;
  } 
  
  function sanitize_email_addresses($entered_email_addresses){
    $email_addresses = explode(',', $entered_email_addresses);
    $sanitized_email_addresses = [];
    foreach($email_addresses as $email_address){
      $sanitized_email_addresses[] = sanitize_email($email_address);
    }

    return implode(',', $sanitized_email_addresses);
  }

  function log_email_reports($email_addresses, $report_ids){
    global $wpdb;

    $emails = explode(',', $email_addresses);
    $email_domains = [];
    foreach($emails as $email){
      $email_parts = explode('@', $email);
      $email_domains[] = $email_parts[1];
    }

    $email_domains_str = implode(',', $email_domains);

    $wpdb->insert(
      'emailed_reports',
      array(
        'email_domains' => $email_domains_str,
        'report_ids' => $report_ids,
      )
    );

    return $wpdb->insert_id;
  }

  function process_admin_tasks(){
    require_once 'delete_old_reports.php';

    if(isset($_GET['email_admin_reports'])){
      require_once 'reports/email_admin_reports.php';
    }
  }

  function report_button_container(){
    $article_id = get_the_ID();
    $nonce = wp_create_nonce('report_button_' . $article_id);

    $btn_container = '<div class="report-button hidden-print" data-article_id="' . $article_id . '" data-nonce="' . $nonce . '"></div>';
    echo $btn_container;
  }

  function record_report_save(){
    $article_id = $_POST['article_id'];
    //verify nonce
    if(check_ajax_referer('report_button_' . $article_id, 'nonce', false) == false){
      wp_send_json_error();
    }
    else{
      global $wpdb;
      $wpdb->insert(
        'saved_reports',
        array(
          'article_id' => $article_id
        ),
        array(
          '%d'
        )
      );
    }
  }

  function log_search_results($post_ids, $args){
    $count_field_acf_key = 'field_5bf56db6a2d37';

    foreach($post_ids as $post_id){
      $count = get_field($count_field_acf_key, $post_id);
      $count++;
      update_field($count_field_acf_key, $count, $post_id);
    }

    return $post_ids;
  }

  function create_acf_field_groups(){
    require_once 'acf_fields.php';
  }
}

new ralf_report;

if(is_admin()){
  require_once 'ralf-dashboard.php';
}