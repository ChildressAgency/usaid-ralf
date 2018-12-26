<?php
define('USAIDRALF_TEMPLATE_DIR', dirname(__FILE__));

add_action('wp_footer', 'show_template');
function show_template() {
	global $template;
	print_r($template);
}

add_action('wp_enqueue_scripts', 'jquery_cdn');
function jquery_cdn(){
  if(!is_admin()){
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, null, true);
    wp_enqueue_script('jquery');
  }
}

add_action('wp_enqueue_scripts', 'usaidralf_scripts', 100);
function usaidralf_scripts(){
  wp_register_script(
    'bootstrap-script', 
    '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', 
    array('jquery'), 
    '', 
    true
  );

  wp_register_script(
    'js-cookie',
    get_template_directory_uri() . '/js/js-cookie.js',
    array('jquery'),
    '',
    true
  );

  wp_register_script(
    'usaidralf-scripts', 
    get_template_directory_uri() . '/js/usaidralf-scripts.js', 
    array('jquery'), 
    '', 
    true
  ); 
  
  wp_enqueue_script('bootstrap-script');
  wp_enqueue_script('js-cookie');
  wp_enqueue_script('usaidralf-scripts');  
}

add_action('wp_enqueue_scripts', 'usaidralf_styles');
function usaidralf_styles(){
  wp_register_style('bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
  wp_register_style('google-fonts', '//fonts.googleapis.com/css?family=Lato:400,400i,700');
  wp_register_style('usaidralf', get_template_directory_uri() . '/style.css');
  
  wp_enqueue_style('bootstrap-css');
  wp_enqueue_style('google-fonts');
  wp_enqueue_style('dashicons');
  wp_enqueue_style('usaidralf');
}

add_action('after_setup_theme', 'usaidralf_theme_setup');
function usaidralf_theme_setup(){
  add_theme_support('post-thumbnails');
  register_nav_menu( 'header-nav', 'Header Navigation' );
  load_theme_textdomain('usaidralf', get_template_directory() . '/languages');
}

require_once USAIDRALF_TEMPLATE_DIR . '/includes/class-wp_bootstrap_navwalker.php';

function usaidralf_header_fallback_menu(){ ?>
      
  <div id="navbar" class="collapse navbar-collapse">
    <ul class="nav navbar-nav navbar-right">
      <li<?php if(is_front_page()){ echo ' class="active"'; } ?>><a href="<?php echo home_url(); ?>">Home</a></li>
      <li<?php if(is_page('about')){ echo ' class="active"'; } ?>><a href="<?php echo home_url('about'); ?>">About</a></li>
      <li<?php if(is_page('contact')){ echo ' class="active"'; } ?>><a href="<?php echo home_url('contact'); ?>">Contact</a></li>
      <li<?php if(is_page('view-report')){ echo ' class="active"'; } ?>><a href="<?php echo home_url('view-report'); ?>">View Report</a></li>
    </ul>
  </div>

<?php }

require_once USAIDRALF_TEMPLATE_DIR . '/includes/template_pagination.php';
require_once USAIDRALF_TEMPLATE_DIR . '/includes/template_cpts.php';
add_action('init', 'usaidralf_create_post_type');

require_once USAIDRALF_TEMPLATE_DIR . '/includes/class-usaidralf_sector_selector_widget.php';
require_once USAIDRALF_TEMPLATE_DIR . '/includes/class-usaidralf_search_history_widget.php';
add_action('widgets_init', 'usaidralf_widgets_init');
function usaidralf_widgets_init(){
  register_sidebar(array(
    'name' => __('RALF Sidebar', 'usaidralf'),
    'id' => 'ralf-sidebar',
    'description' => __('Sidebar for the RALF results pages.', 'usaidralf'),
    'before_widget' => '<div class="sidebar-section">',
    'after_widget' => '</div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>'
  ));
}

add_action('widgets_init', 'usaidralf_load_widget');
function usaidralf_load_widget(){
  register_widget('usaidralf_sector_selector_widget');
  register_widget('usaidralf_search_history_widget');
  //register_widget('usaidralf_view_report_widget');
}

add_filter('acf/fields/relationship/result/key=field_5a980a2e5519d', 'usaidralf_related_impacts_relationship_display', 10, 4);
function usaidralf_related_impacts_relationship_display($title, $post, $field, $post_id){
  //$impact_tag_names = get_field('impact_tag_names', $post->ID);
  //$impact_tag_names = get_the_tags($post->ID);
  $impact_tag_names = get_the_terms($post->ID, 'impact_tags');
  $impact_tag_name_list = array();

  if(!empty($impact_tag_names)){
    foreach($impact_tag_names as $impact_tag_name){
      $impact_tag_name_list[] = $impact_tag_name->name;
    }

    $title .= ' [' . implode(', ', $impact_tag_name_list) . ']';
  }

  return $title;
}

//search only specific custom post types
add_filter('pre_get_posts','usaidralf_searchfilter');
function usaidralf_searchfilter($query){

  if ($query->is_search && !is_admin() ) {
    $query->set('post_type',array('activities', 'impacts', 'resources'));
  }
 
  return $query;
}

function get_field_excerpt($field_name){
  global $post;
  $text = get_field($field_name);
  if($text != ''){
    $text = strip_shortcodes($text);
    $text = apply_filters('the_content', $text);
    $text = str_replace(']]&gt;', ']]&gt;', $text);
    $excerpt_length = 20;
    $excerpt_more = apply_filters('excerpt_more', ' ', '[...]');
    $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
  }
  return apply_filters('the_excerpt', $text);
}

function usaidralf_get_impacts_by_sector($impact_ids){
  global $wpdb;
  $impact_ids_placeholder = implode(', ', array_fill(0, count($impact_ids), '%d'));
  $impacts_with_sector = $wpdb->get_results($wpdb->prepare("
    SELECT $wpdb->posts.ID AS impact_id, $wpdb->posts.post_title AS impact_title, $wpdb->posts.guid AS impact_link, $wpdb->terms.name AS sector, $wpdb->terms.term_id as sector_id, $wpdb->posts.post_content AS impact_description
    FROM $wpdb->posts
      JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
      JOIN $wpdb->terms ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
      JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
    WHERE $wpdb->term_taxonomy.taxonomy = 'sectors'
      AND $wpdb->posts.ID IN($impact_ids_placeholder)
      AND post_type = 'impacts'", $impact_ids));

  $impacts_by_sector = array();
  foreach($impacts_with_sector as $sector){
    $impacts_by_sector[$sector->sector]['sector_name'] = $sector->sector;
    $impacts_by_sector[$sector->sector]['sector_id'] = $sector->sector_id;
    $impacts_by_sector[$sector->sector]['impacts'][] = $sector;
  }

  ksort($impacts_by_sector);
  return $impacts_by_sector;
}

//$article_type can be impacts (default) or resources
function usaidralf_get_related_activities($article_id, $article_type = 'impacts'){
  $meta_key = 'related_' . $article_type;
  $activities = new WP_Query(array(
    'post_type' => 'activities',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_query' => array(
      array(
        'key' => $meta_key,
        'value' => '"' . $article_id . '"',
        'compare' => 'LIKE'
      )
    )
  ));

  return $activities;
}

//only for resources cpt
function usaidralf_get_related_impacts($resource_id){
  $impacts = new WP_Query(array(
    'post_type' => 'impacts',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_query' => array(
      array(
        'key' => 'related_resources',
        'value' => '"' . $resource_id . '"',
        'compare' => 'LIKE'
      )
    )
  ));

  return $impacts;
}

function usaidralf_show_article_meta($article_type, $article_id){
  switch($article_type){
    case 'impacts':
      $bg_color = get_field('impacts_color', 'option');
      $btn_text = __('Impact', 'usaidralf');
      $article_class = 'article-type';
    break;

    case 'activities':
      $bg_color = get_field('activities_color', 'option');
      $btn_text = __('Activity', 'usaidralf');
      $article_class = 'article-type';
    break;

    case 'resources':
      $bg_color = get_field('resources_color', 'option');
      $btn_text = __('Resource', 'usaidralf');
      $article_class = 'article-type resource-article-type';
    break;

    default:
      $bg_color = '';
      $btn_text = '';
      $article_class = 'article-type';
  }
  echo '<span class="' . $article_class . '" style="background-color:' . $bg_color . ';">' . $btn_text . '</span>';

  //list sector buttons
  $sectors = get_the_terms($article_id, 'sectors');
  if($sectors){
    $parent_selected = false;
    foreach($sectors as $sector){
      $sector_name = $sector->name;
      $sector_color = get_field('sector_color', 'sectors_' . $sector->term_id);
      $sector_url = esc_url(get_term_link($sector->term_id), 'sectors');

      if($sector->parent == 0){ $parent_selected = true; }

      if($sector->parent > 0){
        if($parent_selected == false){
          $sector_parent = get_term($sector->parent, 'sectors');
          $sector_parent_color = get_field('sector_color', 'sectors_' . $sector_parent->term_id);
          $sector_parent_url = esc_url(get_term_link($sector_parent->term_id), 'sectors');

          echo '<a href="' . $sector_parent_url . '" class="meta-btn btn-sector hidden-print" style="background-color:' . $sector_parent_color . ';">' . $sector_parent->name . '</a>';
        }
        echo '<a href="' . $sector_url . '" class="meta-btn btn-sector hidden-print" style="background-color:' . $sector_color . '">' . $sector_name . '</a>';
      }
      else{
        echo '<a href="' . $sector_url . '" class="meta-btn btn-sector hidden-print" style="background-color:' . $sector_color . ';">' . $sector_name . '</a>';
      }
    }
  }

  if($article_type == 'impacts' || $article_type == 'resources'){
    //activities button, w/ count
    $related_activities = usaidralf_get_related_activities($article_id, $article_type);
    $num_activities = $related_activities->post_count;

    if(($article_type == 'impacts') || ($article_type == 'resources' && $num_activities > 0)){
      echo '<a href="' . get_permalink($article_id) . '" class="meta-btn btn-activities hidden-print" style="background-color:' . get_field('activities_color', 'option') . ';">' . sprintf(__('Activities (%d)', 'usaidralf'), $num_activities) . '</a>';
    }
  }

  if($article_type == 'resources'){
    $related_impacts = usaidralf_get_related_impacts($article_id);
    $num_impacts = $related_impacts->post_count;

    if($num_impacts > 0){
      echo '<a href="' . get_permalink($article_id) . '" class="meta-btn btn-impacts" style="background-color:' . get_field('impacts_color', 'option') . ';">' . sprintf(__('Impacts (%d)', 'usaidralf'), $num_impacts) . '</a>';
    }

    $resource_types = get_the_terms($article_id, 'resource_types');
    $parent_selected = false;
    foreach($resource_types as $resource_type){
      $resource_type_name = $resource_type->name;
      $resource_type_color = get_field('resource_type_color', 'resource_types_' . $resource_type->term_id);
      $resource_type_url = esc_url(get_term_link($resource_type->term_id), 'resource_types');

      if($resource_type->parent == 0){ $parent_selected = true; }

      if($resource_type->parent > 0){
        if($parent_selected == false){
          $resource_type_parent = get_term($resource_type->parent, 'resource_types');
          $resource_type_parent_color = get_field('resource_type_color', 'resource_types_' . $resource_type_parent->term_id);
          $resource_type_parent_url = esc_url(get_term_link($resource_type_parent->term_id), 'resource_types');

          echo '<a href="' . $resource_type_parent_url . '" class="meta-btn btn-sector hidden-print" style="background-color:' . $resource_type_parent_color . ';">' . $resource_type_parent->name . '</a>';
        }

        echo '<a href="' . $resource_type_url . '" class="meta-btn btn-sector hidden-print" style="background-color:' . $resource_type_color . ';">' . $resource_type_name . '</a>';
      }
      else{
        echo '<a href="' . $resource_type_url . '" class="meta-btn btn-sector hidden-print" style="background-color:' . $resource_type_color . ';">' . $resource_type_name . '</a>';
      }
    }

    echo '<a href="' . get_field('original_resource_url', $article_id) . '" class="meta-btn btn-sector resource-article-type" target="_blank">' . __('Source', 'usaidralf') . '</a>';
  }
}

// add the filter for your relationship field
// https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-reciprocal-relationship.php
	add_filter('acf/update_value/key=field_5a980a2e5519d', 'acf_reciprocal_relationship', 10, 3);
	// if you are using 2 relationship fields on different post types
	// add second filter for that fields as well
	add_filter('acf/update_value/key=field_5a980a8d64d2a', 'acf_reciprocal_relationship', 10, 3);
	
	function acf_reciprocal_relationship($value, $post_id, $field) {
		
		// set the two fields that you want to create
		// a two way relationship for
		// these values can be the same field key
		// if you are using a single relationship field
		// on a single post type
		
		// the field key of one side of the relationship
		$key_a = 'field_5a980a2e5519d';
		// the field key of the other side of the relationship
		// as noted above, this can be the same as $key_a
		$key_b = 'field_5a980a8d64d2a';
		
		// figure out wich side we're doing and set up variables
		// if the keys are the same above then this won't matter
		// $key_a represents the field for the current posts
		// and $key_b represents the field on related posts
		if ($key_a != $field['key']) {
			// this is side b, swap the value
			$temp = $key_a;
			$key_a = $key_b;
			$key_b = $temp;
		}
		
		// get both fields
		// this gets them by using an acf function
		// that can gets field objects based on field keys
		// we may be getting the same field, but we don't care
		$field_a = acf_get_field($key_a);
		$field_b = acf_get_field($key_b);
		
		// set the field names to check
		// for each post
		$name_a = $field_a['name'];
		$name_b = $field_b['name'];
		
		// get the old value from the current post
		// compare it to the new value to see
		// if anything needs to be updated
		// use get_post_meta() to a avoid conflicts
		$old_values = get_post_meta($post_id, $name_a, true);
		// make sure that the value is an array
		if (!is_array($old_values)) {
			if (empty($old_values)) {
				$old_values = array();
			} else {
				$old_values = array($old_values);
			}
		}
		// set new values to $value
		// we don't want to mess with $value
		$new_values = $value;
		// make sure that the value is an array
		if (!is_array($new_values)) {
			if (empty($new_values)) {
				$new_values = array();
			} else {
				$new_values = array($new_values);
			}
		}
		
		// get differences
		// array_diff returns an array of values from the first
		// array that are not in the second array
		// this gives us lists that need to be added
		// or removed depending on which order we give
		// the arrays in
		
		// this line is commented out, this line should be used when setting
		// up this filter on a new site. getting values and updating values
		// on every relationship will cause a performance issue you should
		// only use the second line "$add = $new_values" when adding this
		// filter to an existing site and then you should switch to the
		// first line as soon as you get everything updated
		// in either case if you have too many existing relationships
		// checking end updated every one of them will more then likely
		// cause your updates to time out.
		//$add = array_diff($new_values, $old_values);
		$add = $new_values;
		$delete = array_diff($old_values, $new_values);
		
		// reorder the arrays to prevent possible invalid index errors
		$add = array_values($add);
		$delete = array_values($delete);
		
		if (!count($add) && !count($delete)) {
			// there are no changes
			// so there's nothing to do
			return $value;
		}
		
		// do deletes first
		// loop through all of the posts that need to have
		// the recipricol relationship removed
		for ($i=0; $i<count($delete); $i++) {
			$related_values = get_post_meta($delete[$i], $name_b, true);
			if (!is_array($related_values)) {
				if (empty($related_values)) {
					$related_values = array();
				} else {
					$related_values = array($related_values);
				}
			}
			// we use array_diff again
			// this will remove the value without needing to loop
			// through the array and find it
			$related_values = array_diff($related_values, array($post_id));
			// insert the new value
			update_post_meta($delete[$i], $name_b, $related_values);
			// insert the acf key reference, just in case
			update_post_meta($delete[$i], '_'.$name_b, $key_b);
		}
		
		// do additions, to add $post_id
		for ($i=0; $i<count($add); $i++) {
			$related_values = get_post_meta($add[$i], $name_b, true);
			if (!is_array($related_values)) {
				if (empty($related_values)) {
					$related_values = array();
				} else {
					$related_values = array($related_values);
				}
			}
			if (!in_array($post_id, $related_values)) {
				// add new relationship if it does not exist
				$related_values[] = $post_id;
			}
			// update value
			update_post_meta($add[$i], $name_b, $related_values);
			// insert the acf key reference, just in case
			update_post_meta($add[$i], '_'.$name_b, $key_b);
		}
		
		return $value;
		
} // end function acf_reciprocal_relationship

if(function_exists('acf_add_options_page')){
  acf_add_options_page(array(
    'page_title' => __('General Settings', 'usaidralf'),
    'menu_title' => __('General Settings', 'usaidralf'),
    'menu_slug' => 'general-settings',
    'capability' => 'edit_posts',
    'redirect' => false
  ));

  acf_add_options_sub_page(array(
    'page_title' => __('Activities Settings', 'usaidralf'),
    'menu_title' => __('Activities Settings', 'usaidralf'),
    'parent_slug' => 'edit.php?post_type=activities'
  ));
  acf_add_options_sub_page(array(
    'page_title' => __('Impacts Settings', 'usaidralf'),
    'menu_title' => __('Impacts Settings', 'usaidralf'),
    'parent_slug' => 'edit.php?post_type=impacts'
  ));
  acf_add_options_sub_page(array(
    'page_title' => __('Resources Settings', 'usaidralf'),
    'menu_title' => __('Resources Settings', 'usaidralf'),
    'parent_slug' => 'edit.php?post_type=resources'
  ));
}
  

/*********************
* searchWP functions *
*********************/

add_action('init', 'usaidralf_update_search_history');
function usaidralf_update_search_history(){
  $new_search_terms_list = usaidralf_get_search_history();
  
  if($new_search_terms_list != ''){
    $cookie_lifetime = 30; //days
    $date_of_expiry = time() + 60 * 60 * 24 * $cookie_lifetime;
    setcookie("STYXKEY_usaidralf_search_history", $new_search_terms_list, $date_of_expiry, "/");
  }
}

function usaidralf_get_search_history(){
  //get new search term if its there
  $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

  if(isset($_COOKIE['STYXKEY_usaidralf_search_history'])){
    $search_terms_list = $_COOKIE['STYXKEY_usaidralf_search_history'];
    //put search terms into array
    $search_terms = explode(',', $search_terms_list);

    $filter_chars = '()^;<>/\'"!';
    //don't do anything if the search term is empty or already in the list
    //also dont do anything if the search term has an invalid char ($filter_chars)
    if(($search_term != '') 
      && (!in_array($search_term, $search_terms))  
      && (strpbrk($search_term, $filter_chars) === false)){

      //get number of terms to save to history
      $history_limit = get_field('search_term_history_limit', 'option');
    
      //if we are at history limit remove first search term
      if((count($search_terms) == $history_limit)){
        array_shift($search_terms);
      }

      //add the new search term to end of array if there is one
      array_push($search_terms, $search_term);
    }
    //convert terms array to string and return
    $new_search_terms_list = implode(',', $search_terms);

    return $new_search_terms_list;
  }
  else{ //no cookie, must be first search or they've been cleared with js function
    return $search_term;
  }
}

add_filter('searchwp_query_join', 'usaidralf_join_term_relationships', 10, 3);
function usaidralf_join_term_relationships($sql, $post_type, $engine){
  global $wpdb;

  return "LEFT JOIN {$wpdb->prefix}term_relationships as swp_tax_rel ON swp_tax_rel.object_id = {$wpdb->prefix}posts.ID";
}

add_filter('searchwp_weight_mods', 'usaidralf_weight_priority_keywords');
function usaidralf_weight_priority_keywords($sql){
  $searched_keyword = get_search_query();
  $searched_keyword_term = get_term_by('slug', $searched_keyword, 'priority_keywords');

  if($searched_keyword_term != false){
    $priority_keyword_id = $searched_keyword_term->term_id;
    $additional_weight = 1000;

    return $sql . " + (IF ((swp_tax_rel.term_taxonomy_id = {$priority_keyword_id}), {$additional_weight}, 0))";
  }
}

//RewriteRule ^/view-report/([^.]*)$ /view-report/report_id=$1 [PT]
//add_filter('query_vars', 'usaidralf_register_custom_query_vars');
function usaidralf_register_custom_query_vars($vars){
  //array_push($vars, 'report_id');
  $vars[] = 'report_id';
  return $vars;
}
add_action('init', 'usaidralf_rewrite_report_url');
function usaidralf_rewrite_report_url(){
  //add_rewrite_tag('%report_id%', '([0-9]*)', 'report_id=');
  add_rewrite_tag('%report_id%', '([^&]+)');

  add_rewrite_rule('^view-report/([^.]*)$', 'index.php?pagename=view-report&report_id=$matches[1]', 'top');
  //add_rewrite_rule('/view-report/([^.]*)$', 'view-report?report_id=$matches[1]', 'top');
}