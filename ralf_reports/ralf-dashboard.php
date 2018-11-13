<?php

class ralf_dashboard{
  static $instance;

  public function __construct(){
    add_action('wp_dashboard_setup', array($this, 'ralf_dashboard_setup'));
  }

  function ralf_dashboard_setup(){
    wp_add_dashboard_widget(
      'emailed-reports',
      'Emailed Reports',
      array($this, 'show_emailed_reports')
    );
  }

  public function show_emailed_reports(){
    require_once 'reports/emailed_reports.php';
  }

  public static function get_instance(){
    if(!isset(self::$instance)){
      self::$instance = new self();
    }

    return self::$instance;
  }
}

add_action('plugins_loaded', function(){
  ralf_dashboard::get_instance();
});