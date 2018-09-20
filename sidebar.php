<aside class="results-sidebar hidden-xs">
  <?php dynamic_sidebar('ralf-sidebar'); ?>
  <?php if(is_singular('activities') || is_singular('impacts')): ?>
    <div class="sidebar-section">
      <div class="report-button" data-article_id="<?php echo get_the_ID(); ?>">
        <a href="#" id="sidebar-report-button" class="save-to-report">Save To Report</a>
      </div>
    </div>
  <?php endif; ?>
  <?php 
    $search_history = usaidralf_get_search_history();
    if($search_history != ''){
      $search_history_terms = explode(',', $search_history);

      echo '<div class="sidebar-section"><h4>SEARCH HISTORY</h4>';
      echo '<a href="#" id="clear-search-history">[clear history]</a><ul>';
      foreach($search_history_terms as $search_term){
        echo '<li><a href="' . esc_url(add_query_arg('s', $search_term, home_url())) . '">' . $search_term . '</a></li>';
      }
      echo '</ul></div>';
    }
  ?>
</aside>
