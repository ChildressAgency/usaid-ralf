<?php
if(!defined('ABSPATH')){ exit; }

class view_report_widget extends WP_Widget{
	function __construct(){
		parent::__construct(
			'wuaidralf_view_report_widget',
			__('View Report Widget', 'ralfreports'),
			array('description' => __('Show the View Report button', 'ralfreports'))
		);
	}

	public function widget($args, $instance){
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		//if(!empty($title)){
		//	echo $args['before_title'] . $title . $args['after_title'];
    //}
    
    $article_count = $this->get_article_count();

    echo '<h4 class="view-report-widget-title"><a href="' . home_url('view-report') . '">' . $title . ' (' . $article_count . ')</a></h4>';

		echo $args['after_widget'];
	}

	public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		}
		else{
			$title = __('New title', 'ralfreports');
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
  
  protected function get_article_count(){
    if(isset($_COOKIE['STYXKEY_report_ids'])){
      $report_ids_cookie = $_COOKIE['STYXKEY_report_ids'];
      $report_ids = explode(',', $report_ids_cookie);

      return count($report_ids);
    }
    else{
      return 0;
    }
  }
}
