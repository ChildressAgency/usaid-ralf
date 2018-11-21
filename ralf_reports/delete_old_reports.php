<?php
// Exit if accessed directly
if (!defined('ABSPATH')){ exit; }

$how_long_to_store_reports = get_field('how_long_to_store_reports', 'option');

global $wpdb;

$wpdb->query($wpdb->prepare("
  DELETE from emailed_reports
  WHERE datediff(now(), email_date) > %d", $how_long_to_store_reports));