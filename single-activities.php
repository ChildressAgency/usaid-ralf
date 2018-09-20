<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-9">
          <main class="result">
            <div class="go-back">
              <a href="javascript:history.back(-1);">BACK</a>
            </div>
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
              <article class="ralf-article">
                <header class="result-header">
                  <span class="result-type-icon activity" data-toggle="tooltip" data-placement="top" title="Activity"></span>
                  <div class="sector-icon sector-icon-small"></div>
                  <h1><?php the_title(); ?></h1>
                </header>
                <section class="result-content">
                  <div class="activity-description">
                    <p><?php the_content(); ?></p>
                  </div>
                  <?php if(get_field('conditions')): ?>
                    <div class="activity-conditions">
                      <h2>POTENTIAL CONDITIONS</h2>
                      <?php the_field('conditions'); ?>
                    </div>
                  <?php endif; ?>
                </section>
               
                <?php 
                  $impact_ids = get_field('related_impacts', false, false);
                  if(!empty($impact_ids)):
                    $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids); ?>

                    <section class="impact-by-sector">
                      <h2>IMPACT BY SECTOR<span class="dashicons dashicons-excerpt-view" data-toggle="tooltip" data-position="top" title="Expand All"></span></h2>
                      <div class="panel-group" id="impacts-accordion" role="tablist" aria-multiselectable="true">
                        <?php 
                            $i = 0;
                          foreach($impacts_by_sector as $sector):
                            $acf_sector_id = 'sectors_' . $sector['sector_id'];
                            foreach($sector['impacts'] as $impact): ?>

                              <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="impact-title<?php echo $i; ?>">
                                  <h3 class="panel-title">
                                    <a href="#impact<?php echo $i; ?>" role="button" data-toggle="collapse" data-parent="#impacts-accordion"  aria-expanded="false" aria-controls="impact<?php echo $i; ?>">
                                      
                                        <img src="<?php the_field('sector_icon', $acf_sector_id); ?>" class="img-circle" alt="<?php echo $sector['sector_name']; ?>" style="background-color:<?php the_field('sector_color', $acf_sector_id); ?>;" data-toggle="tooltip" data-placement="top" title="<?php echo $sector['sector_name']; ?>" />
                                        <span> <?php echo $impact->impact_title; ?></span>
                                    </a>
                                  </h3>
                                  <a href="<?php echo esc_url(get_term_link((int)$sector['sector_id'], 'sectors')); ?>" class="sector-popout" target="_blank">
                                    <span class="dashicons dashicons-external" data-toggle="tooltip" data-position="top" title="<?php echo $sector['sector_name']; ?>"></span>
                                  </a>
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
                <div class="report-button" data-article_id="<?php echo get_the_ID(); ?>">
                  
                </div>
              </article>
            <?php endwhile; endif; ?>

          </main>
        </div>
        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>