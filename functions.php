<?php

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

add_theme_support('post-thumbnails');

register_nav_menu( 'header-nav', 'Header Navigation' );
/**
 * Class Name: wp_bootstrap_navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class wp_bootstrap_navwalker extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

			if ( $args->has_children )
				$class_names .= ' dropdown';

			if ( in_array( 'current-menu-item', $classes ) )
				$class_names .= ' active';

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

			$atts = array();
			$atts['title']  = ! empty( $item->title )	? $item->title	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

			// If item has_children add atts to a.
			if ( $args->has_children && $depth === 0 ) {
				$atts['href']   		= '#';
                                $atts['href'] = ! empty( $item->url ) ? $item->url : '';
        
        //$atts['data-toggle']	= 'dropdown';
				$atts['class']			= 'dropdown-toggle';
				$atts['aria-haspopup']	= 'true';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */

			 $item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			if ( ! empty( $item->attr_title ) ){
				$item_output .= '&nbsp;<span class="' . esc_attr( $item->attr_title ) . '"></span>';
			}

			$item_output .= ( $args->has_children && 0 === $depth ) ? ' </a>' : '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			echo $fb_output;
		}
	}
}

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

add_action('init', 'usaidralf_create_post_type');
function usaidralf_create_post_type(){
  $activity_labels = array(
    'name' => 'Activities',
    'singular_name' => 'Activity',
    'menu_name' => 'Activities',
    'add_new_item' => 'Add New Activity',
    'search_items' => 'Search Activities'
  );
  $activity_args = array(
    'labels' => $activity_labels,
    'public' => true,
    'menu_position' => 5,
    'supports' => array('title', 'author', 'revisions', 'editor')
  );
  register_post_type('activities', $activity_args);

  $impacts_labels = array(
    'name' => 'Impacts',
    'singular_name' => 'Impact',
    'menu_name' => 'Impacts',
    'add_new_item' => 'Add New Impact',
    'search_items' => 'Search Impacts'
  );
  $impacts_args = array(
    'labels' => $impacts_labels,
    'public' => true,
    'menu_position' => 6,
    'supports' => array('title', 'author', 'revisions', 'editor')
  );
  register_post_type('impacts', $impacts_args);

  register_taxonomy('sectors',
    //array('impacts', 'activities', 'conditions'),
    'impacts',
    array(
      'hierarchical' => true,
      'show_admin_column' => true,
      'public' => true,
      'labels' => array(
        'name' => 'Sectors',
        'singular_name' => 'Sector'
      )
    )
  );
  register_taxonomy('impact_tags',
    'impacts',
    array(
      'hierarchical' => false,
      'show_admin_column' => true,
      'public' => true,
      'labels' => array(
        'name' => 'Impact Tags',
        'singular_name' => 'Impact Tag'
      )
    )
  );
  register_taxonomy('priority_keywords',
    array('impacts', 'activities'),
    array(
      'hierarchical' => false,
      'show_admin_column' => false,
      'public' => true,
      'labels' => array(
        'name' => 'Priority Keywords',
        'singular_name' => 'Priority Keyword'
      )
    )
  );
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
    $query->set('post_type',array('activities', 'impacts'));
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

add_action('widgets_init', 'usaidralf_widgets_init');
function usaidralf_widgets_init(){
  register_sidebar(array(
    'name' => __('RALF Sidebar', 'usaidralf_widget_domain'),
    'id' => 'ralf-sidebar',
    'description' => __('Sidebar for the RALF results pages.', 'usaidralf_widget_domain'),
    'before_widget' => '<div class="sidebar-section">',
    'after_widget' => '</div>',
    'before_title' => '<h4>',
    'after_title' => '</h4>'
  ));
}

add_action('widgets_init', 'usaidralf_load_widget');
function usaidralf_load_widget(){
  register_widget('usaidralf_sector_selector_widget');
  register_widget('usaidralf_view_report_widget');
}

class usaidralf_sector_selector_widget extends WP_Widget{
	function __construct(){
		parent::__construct(
			'usaidralf_sector_selector_widget',
			__('Sector Selector Widget', 'usaidralf_widget_domain'),
			array('description' => __('Show a select field for displaying RALF by Sector', 'usaidralf_widget_domain'))
		);
	}

	public function widget($args, $instance){
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		if(!empty($title)){
			echo $args['before_title'] . $title . $args['after_title'];
		}

    $sectors = get_terms(array('taxonomy' => 'sectors', 'orderby' => 'term_group', 'parent' => 0));
    if($sectors){
      echo '<ul>';
      foreach($sectors as $sector){
        echo '<li><a href="' . esc_url(get_term_link($sector)) . '">' . $sector->name . '</a></li>';
        $sub_sectors = get_terms(array('taxonomy' => 'sectors', 'orderby' => 'name', 'parent' => $sector->term_id));
        if(!empty($sub_sectors) && !is_wp_error($sub_sectors)){
          foreach($sub_sectors as $sub_sector){
            echo '<li><a href="' . esc_url(get_term_link($sub_sector)) . '"> - ' . $sub_sector->name . '</a></li>';
          }
        }
      }
    }
    echo '</ul>';
		echo $args['after_widget'];
	}

	public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		}
		else{
			$title = __('New title', 'usaidralf_widget_domain');
		}
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
	<?php
	}

	public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
}

class usaidralf_view_report_widget extends WP_Widget{
	function __construct(){
		parent::__construct(
			'wuaidralf_view_report_widget',
			__('View Report Widget', 'usaidralf_widget_domain'),
			array('description' => __('Show the View Report button', 'usaidralf_widget_domain'))
		);
	}

	public function widget($args, $instance){
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		if(!empty($title)){
			echo $args['before_title'] . $title . $args['after_title'];
		}

    echo '<a href="' . home_url('view-report') . '">View Report</a>';

		echo $args['after_widget'];
	}

	public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		}
		else{
			$title = __('New title', 'usaidralf_widget_domain');
		}
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
	<?php
	}

	public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
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

function usaidralf_get_related_activities($impact_id){
  $activities = new WP_Query(array(
    'post_type' => 'activities',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_query' => array(
      array(
        'key' => 'related_impacts',
        'value' => '"' . $impact_id . '"',
        'compare' => 'LIKE'
      )
    )
  ));

  return $activities;
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
      'page_title' => 'General Settings',
      'menu_title' => 'General Settings',
      'menu_slug' => 'general-settings',
      'capability' => 'edit_posts',
      'redirect' => false
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