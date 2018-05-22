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
    'usaidralf-scripts', 
    get_template_directory_uri() . '/js/usaidralf-scripts.js', 
    array('jquery'), 
    '', 
    true
  ); 
  
  wp_enqueue_script('bootstrap-script');
  wp_enqueue_script('usaidralf-scripts');  
}

add_action('wp_enqueue_scripts', 'usaidralf_styles');
function usaidralf_styles(){
  wp_register_style('bootstrap-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
  wp_register_style('google-fonts', '//fonts.googleapis.com/css?family=Lato:400,400i,700');
  wp_register_style('usaidralf', get_template_directory_uri() . '/style.css');
  
  wp_enqueue_style('bootstrap-css');
  wp_enqueue_style('google-fonts');
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

function usaidralf_fallback_header_menu(){ ?>
      
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
      'labels' => array(
        'name' => 'Impact Tags',
        'singular_name' => 'Impact Tag'
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

    $sectors = get_terms(array('taxonomy' => 'sectors', 'orderby' => 'name'));
    if($sectors){
      echo '<ul>';
      foreach($sectors as $sector){
        echo '<li><a href="' . esc_url(get_term_link($sector)) . '">' . $sector->name . '</a></li>';
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
    WHERE $wpdb->term_taxonomy.taxonomy = 'sectors
      AND $wpdb->posts.ID IN($impact_ids_placeholder)
      AND post_type = 'impacts'", $impact_ids));

  $impacts_by_sector = array();
  foreach($impacts_with_sector as $sector){
    $impacts_by_sector[$sector->sector]['sector_name'] = $sector->sector;
    $impacts_by_sector[$sector->sector]['sector_id'] = $sector->sector_id;
    $impacts_by_sector[$sector->sector]['impacts'][] = $sector;
  }

  return ksort($impacts_by_sector);
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