<?php get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <main class="results-list">
          <?php $current_sector = get_queried_object(); ?>

          <h1 class="sector-title"><img src="<?php the_field('sector_icon', 'sectors_' . $current_sector->term_id); ?>" class="img-circle img-responsive" alt="<?php echo $current_sector->name . ' ' . __('Sector', 'usaidralf'); ?>" style="background-color:<?php the_field('sector_color', 'sectors_' . $current_sector->term_id); ?>" /><?php echo $current_sector->name; ?></h1>

          <ul class="nav nav-pills nav-justified" role="tablist">
            <li role="presentation" class="active"><a href="#impacts" aria-controls="impacts" role="tab" data-toggle="tab">Impacts / Activities</a></li>
            <li role="presentation"><a href="#resources" aria-controls="resources" role="tab" data-toggle="tab">Resources</a></li>
          </ul>

          <div class="tab-content">
            <div id="impacts" class="tab-pane fade in active" role="tabpanel">
              <?php
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $impacts = new WP_Query(array(
                  'post_type' => 'impacts',
                  'post_status' => 'publish',
                  'paged' => $paged,
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'sectors',
                      'field' => 'term_id',
                      'terms' => $current_sector->term_id
                    )
                  )
                ));

                if($impacts->have_posts()): while($impacts->have_posts()): $impacts->the_post();
                  $impact_id = get_the_ID(); ?>

                  <div class="loop-item">
                    <h2 class="loop-item-title">
                      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="loop-item-meta">
                      <?php usaidralf_show_article_meta('impacts', $impact_id); ?>
                    </div>
                  </div>
              <?php endwhile; else: ?>
                <p><?php _e('Sorry, nothing was found.', 'usaidralf'); ?></p>
              <?php endif; usaidralf_pagination(); wp_reset_postdata(); ?>
            </div>

            <div id="resources" class="tab-pane fade" role="tabpanel">
              <?php
                $resources = new WP_Query(array(
                  'post_type' => 'resources',
                  'post_status' => 'publish',
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'sectors',
                      'field' => 'term_id',
                      'terms' => $current_sector->term_id
                    )
                  )
                ));

                if($resources->have_posts()): while($resources->have_posts()): $resources->the_post();
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
                <p><?php _e('Sorry, no resources found.', 'usaidralf'); ?></p>
              <?php endif; wp_reset_postdata(); ?>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>