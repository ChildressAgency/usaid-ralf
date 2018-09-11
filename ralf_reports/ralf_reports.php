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
    include 'view-report.php';
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

  public function email_rtf_form(){
    
  }
}

new ralf_report;