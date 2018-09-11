<?php
/*
  Plugin Name: Email RTF Report
  Description: Email an RTF version of RALF report
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 1.0
  Test Domain: emailrtfreport
*/

class Email_rtf_report{
  public function __construct(){
    //add_filter('the_content', array($this, 'email_button'), 10, 1);
    add_shortcode('email_form', array($this, 'email_rtf_form'));

    add_action('wp_enqueue_scripts', array($this, 'scripts'));

    add_action('wp_ajax_nopriv_send_rtf_report', array($this, 'send_rtf_report'));
    add_action('wp_ajax_send_rtf_report', array($this, 'send_rtf_report'));
  }

  public function email_rtf_form(){
    //if(!is_page('view-report')){ return $content; }

    $nonce = wp_create_nonce('email_rtf_report_' . get_the_ID());

    $form_content = '<div class="email-report">
                      <h3>Email this report</h3>
                      <div class="form-group">
                        <input type="text" id="email-addresses" name="email-addresses" class="form-control" placeholder="' . __('Enter a comma-separated list of email addresses.', 'emailrtfreport') . '" />
                      </div>
                      <button class="btn-main btn-report send-email" data-nonce="' . $nonce . '" data-post_id="' . get_the_ID() . '">' . __('Send Email', 'emailrtfreport') . '</button>
                      <p class="email-response"></p>
                    </div>';
    
    return $form_content;
  }

  function scripts(){
    wp_enqueue_style('email-rtf-report', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('email-rtf-report', plugin_dir_url(__FILE__) . 'js/scripts.js', array('jquery'), null, true);

    wp_localize_script('email-rtf-report', 'ralf_settings', array(
      'ralf_ajaxurl' => admin_url('admin-ajax.php'),
      'send_label' => __('Email report', 'emailrtfreport'),
      'error' => __('Sorry, something went wrong. Please try again.', 'emailrtfreport')
    ));
  }

  function send_rtf_report(){
    $data = $_POST;

    if(check_ajax_referer('email_rtf_report_' . $data['post_id'], 'nonce', false) == false){
      wp_send_json_error();
    }

    //$post_title = get_the_title(intval($data['post_id']));

    $to = $data['email-addresses'];
    $subject = 'Your RALF Impact Report';
    $message = 'Please find your RALF Impact Report attached';

    $result = wp_mail($to, $subject, $message);

    if($result == true){
      wp_send_json_success(__('Report sent!', 'emailrtfreport'));
    }
    else{
      wp_send_json_error();
    }
  }
}

new Email_rtf_report;