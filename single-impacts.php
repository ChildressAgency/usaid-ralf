<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
        <div class="col-sm-8 col-md-9">
          <main class="result">
            <?php if(have_posts()): while(have_posts()): the_post();
              $impact_id = get_the_ID();
              $sectors = get_the_terms($impact_id, 'sectors'); ?>

              <article class="ralf-article">
                <header class="result-header">
                  <h1><?php the_title(); ?></h1>
                  <div class="result-meta">
                    <?php usaidralf_show_article_meta('impacts', $impact_id, $sectors); ?>
                  </div>
                </header>

                <section class="result-content">
                  <?php the_content(); ?>
                </section>

                <section class="related">
                  <h3><?php _e('Related Activities', 'usaidralf'); ?></h3>
                  <?php 
                    $related_activities = usaidralf_get_related_activities($impact_id);
                    if($related_activities->have_posts()): ?>
                      <ul>
                        <?php while($related_activities->have_posts()): $related_activities->the_post(); ?>
                          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile; ?>
                      </ul>
                  <?php else: ?>
                    <p><?php _e('No related Activities', 'usaidralf'); ?></p>
                  <?php endif; wp_reset_postdata(); ?>
                </section>

                <section class="related">
                  <h3><?php _e('Related Resources', 'usaidralf'); ?></h3>
                  <?php 
                    $related_resources = get_field('related_resources', $impact_id);
                    if($related_resources): ?>
                      <ul>
                        <?php foreach($related_resources as $resource): ?>
                          <li><a href="<?php echo get_permalink($resource); ?>"><?php echo get_the_title($resource); ?></a></li>
                        <?php endforeach; ?>
                      </ul>
                  <?php else: ?>
                    <p><?php _e('No related Resources', 'usaidralf'); ?></p>
                  <?php endif; ?>
                </section>
              </article>
            <?php endwhile; endif; ?>
          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>