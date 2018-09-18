<aside class="results-sidebar hidden-xs">
  <?php dynamic_sidebar('ralf-sidebar'); ?>
  <?php if(is_singular('activities')): ?>
    <div class="sidebar-section">
      <a href="#" id="sidebar-report-button" class="save-to-report">Save To Report</a>
    </div>
  <?php endif; ?>
  <?php 
    $search_history = usaidralf_get_search_history();
    if($search_history != ''){
      $search_history_terms = explode(',', $search_history);

      echo '<div class="sidebar-section"><h4>SEARCH HISTORY</h4>';
      echo '<a href="#" id="clear-search-history">[clear history]</a><ul>';
      foreach($search_history_terms as $search_term){
        echo '<li>' . $search_term . '</li>';
      }
      echo '</ul></div>';
    }
  ?>
</aside>
