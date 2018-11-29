<?php
class usaidralf_sector_selector_widget extends WP_Widget{
	function __construct(){
		parent::__construct(
			'usaidralf_sector_selector_widget',
			__('Sector Selector Widget', 'usaidralf'),
			array('description' => __('Show a select field for displaying RALF by Sector', 'usaidralf'))
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
      echo '<div class="sidebar-section-body"><ul>';
      foreach($sectors as $sector){
        echo '<li><a href="' . esc_url(get_term_link($sector)) . '">' . $sector->name . ' (' . $sector->count . ')' . '</a></li>';
        $sub_sectors = get_terms(array('taxonomy' => 'sectors', 'orderby' => 'name', 'parent' => $sector->term_id));
        if(!empty($sub_sectors) && !is_wp_error($sub_sectors)){
          foreach($sub_sectors as $sub_sector){
            echo '<li><a href="' . esc_url(get_term_link($sub_sector)) . '"> - ' . $sub_sector->name . ' (' . $sub_sector->count . ')' . '</a></li>';
          }
        }
      }
    }
    echo '</ul></div>';
		echo $args['after_widget'];
	}

	public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		}
		else{
			$title = __('New title', 'usaidralf');
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
