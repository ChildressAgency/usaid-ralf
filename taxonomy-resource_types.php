<?php get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <main class="results-list">
          <?php $current_resource_type = get_queried_object(); ?>

          <h1 class="sector-title">
            <?php
              $resource_type_icon = get_field('resource_type_icon', 'resource_types_' . $current_resource_type->term_id);
              if($resource_type_icon): ?>
                <img src="<?php echo $resource_type_icon; ?>" class="img-circle img-responsive" alt="<?php echo $current_resource_type->name . ' ' . __('Resource Type', 'usaidralf'); ?>" style="background-color:<?php the_field('resource_type_color', 'resource_types_' . $current_resource_type->term_id); ?>;" />
            <?php endif; ?>
            <?php echo $current_resource_type->name; ?>
          </h1>

          <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $resources = new WP_Query(array(
              'post_type' => 'resources',
              'post_status' => 'publish',
              'paged' => $paged,
              'tax_query' => array(
                array(
                  'taxonomy' => 'resource_types',
                  'field' => 'term_id',
                  'terms' => $current_resource_type->term_id
                )
              )
            ));

            if($resources->have_posts()): while($resources->have_posts()): 
              $resources->the_post();
              $resource_id = get_the_ID(); ?>

              <div class="loop-item">
                <h2 class="loop-item-title">
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="loop-item-meta">
                  <?php usaidralf_show_article_meta('resources', $resource_id); ?>
                </div>
              </div>
          <?php endwhile; else: ?>
            <p><?php _e('Sorry, nothing was found.', 'usaidralf'); ?></p>
          <?php endif; usaidralf_pagination(); wp_reset_postdata(); ?>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>