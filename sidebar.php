<aside class="results-sidebar hidden-xs">
  <?php dynamic_sidebar('ralf-sidebar'); ?>
  <?php if(is_singular('activities') || is_singular('impacts')): ?>
    <div class="sidebar-section">
      <?php echo do_shortcode('[report_button]'); ?>
    </div>
  <?php endif; ?>
</aside>
