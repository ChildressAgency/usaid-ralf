<aside class="results-sidebar hidden-xs">
  <?php dynamic_sidebar('ralf-sidebar'); ?>
  <?php if(is_singular('activities') || is_singular('impacts')): ?>
    <div class="sidebar-section">
      <?php echo do_shortcode('[report_button]'); ?>
    </div>
  <?php endif; ?>
  <?php 
    $search_history = usaidralf_get_search_history();
    if($search_history != ''){
      $search_history_terms = explode(',', $search_history);

      echo '<div class="sidebar-section"><h4>' . __('SEARCH HISTORY', 'usaidralf') . '</h4>';
      echo '<a href="#" id="clear-search-history">[' . __('clear history', 'usaidralf') . ']</a><ul>';
      foreach($search_history_terms as $search_term){
        echo '<li><a href="' . esc_url(add_query_arg('s', $search_term, home_url())) . '">' . $search_term . '</a></li>';
      }
      echo '</ul></div>';
    }
  ?>
</aside>
