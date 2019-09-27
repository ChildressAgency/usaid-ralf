<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
      <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
        <div class="col-sm-8 col-md-9">
          <main class="result">
            <div class="go-back">
              <a href="javascript:history.back(-1);"><?php _e('BACK', 'usaidralf'); ?></a>
            </div>
            <?php if(have_posts()): while(have_posts()): the_post(); 
              $activity_id = get_the_ID(); ?>

              <article class="ralf-article">
                <header class="result-header">
                  <h1><?php the_title(); ?></h1>
                  <div class="results-meta">
                    <?php usaidralf_show_article_meta('activities', $activity_id); ?>
                  </div>
                </header>

                <section class="result-content">
                  <div class="activity-description">
                    <p><?php the_content(); ?></p>
                  </div>
                  <?php if(get_field('conditions')): ?>
                    <div class="activity-conditions">
                      <h2><?php _e('POTENTIAL CONDITIONS', 'usaidralf'); ?></h2>
                      <?php the_field('conditions'); ?>
                    </div>
                  <?php endif; ?>
                </section>
                  <?php echo do_shortcode('[report_button]'); ?>
               
                <?php 
                  $impact_ids = get_field('related_impacts', false, false);
                  if(!empty($impact_ids)):
                    $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids); ?>

                    <section class="impact-by-sector">
                      <h2><?php _e('IMPACT BY SECTOR', 'usaidralf'); ?><span class="dashicons dashicons-excerpt-view" data-toggle="tooltip" data-position="top" title="<?php _e('Expand All', 'usaidralf'); ?>"></span></h2>
                      <div class="panel-group" id="impacts-accordion" role="tablist" aria-multiselectable="true">
                        <?php 
                            $i = 0;
                          foreach($impacts_by_sector as $sector):
                            $acf_sector_id = 'sectors_' . $sector['sector_id'];
                            foreach($sector['impacts'] as $impact): ?>

                              <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="impact-title<?php echo $i; ?>">
                                  <h3 class="panel-title">
                                    <a href="<?php echo get_permalink($impact->impact_id); ?>" class="sector-popout hidden-print" target="_blank">
                                      <?php echo $impact->impact_title; ?>
                                      <span class="dashicons dashicons-external" data-toggle="tooltip" data-position="top" title="<?php printf(__('Open %s in a new tab'), '"' . $impact->impact_title . '"'); ?>"></span>
                                    </a>
                                  </h3>
                                  <div class="impact-by-sector-meta">
                                    <a href="#impact<?php echo $i; ?>" class="meta-btn report-expand hidden-print collapsed" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="impact<?php echo $i; ?>"></a>
                                    <?php
                                      //get all sectors for this impact to use for meta btns
                                      usaidralf_show_article_meta('impacts', $impact->impact_id); 
                                    ?>
                                  </div>
                                </div>
                                <div class="clearfix"></div>
                                <div id="impact<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="impact-title<?php echo $i; ?>">
                                  <div class="panel-body">
                                    <?php echo $impact->impact_description; ?>
                                  </div>
                                </div>
                              </div>
                            
                          <?php $i++; endforeach; ?>
                        <?php endforeach; ?>
                      </div>
                    </section>
                  <?php endif; ?>

                  <section class="related">
                    <h3><?php _e('Related Resources', 'usaidralf'); ?></h3>
                    <?php 
                      $related_resources = get_field('related_resources', $activity_id);
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