<aside class="results-sidebar hidden-xs">
  <?php dynamic_sidebar('ralf-sidebar'); ?>
  <?php if(is_singular('activities')): ?>
    <div class="sidebar-section">
      <a href="#" id="sidebar-report-button" class="save-to-report">Save To Report</a>
    </div>
  <?php endif; ?>
</aside>
