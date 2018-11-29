<?php
class usaidralf_search_history_widget extends WP_Widget{
  function __construct(){
    parent::__construct(
      'usaidralf_search_history_widget',
      __('Search History Widget', 'usaidralf'),
      array('description' => __('Show the search history', 'usaidralf'))
    );
  }

  public function widget($args, $instance){
    $title = apply_filters('widget_title', $instance['title']);

    echo $args['before_widget'];
    if(!empty($title)){
      echo $args['before_title'] . $title . $args['after_title'];
    }

    $search_history = $this->get_search_history($args['id']);
    if($search_history != ''){
      $search_history_terms = explode(',', $search_history);

      echo '<div class="sidebar-section-body"><ul>';
      foreach($search_history_terms as $search_term){
        echo '<li><a href="' . esc_url(add_query_arg('s', $search_term, home_url())) . '">' . $search_term . '</a></li>';
      }
      echo '</ul>';
      echo '<a href="#" id="clear-search-history">' . __('clear all', 'usaidralf') . '</a></div>';
    }
  }

  protected function get_search_history($widget_id){
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
        $history_limit = get_field('search_term_history_limit', 'widget_' . $widget_id);
      
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