<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-9">
          <main class="result">
            <div class="go-back">
              <a href="javascript:history.back(-1);">BACK</a>
            </div>
            <article>
              <?php if(have_posts()): while(have_posts()): the_post(); ?>
                <header class="result-header">
                  <span class="result-type-icon activity"></span>
                  <div class="sector-icon sector-icon-small"></div>
                  <h1><?php the_title(); ?></h1>
                </header>
                <section class="result-content">
                  <div class="activity-description">
                    <p><?php the_content(); ?></p>
                  </div>
                  <?php if(get_field('conditions')): ?>
                    <div class="activity-conditions">
                      <h2>CONDITIONS</h2>
                      <?php the_field('conditions'); ?>
                    </div>
                  <?php endif; ?>
                </section>
               
                <?php 
                  $impact_ids = get_field('related_impacts', false, false);
                  if(!empty($impact_ids)):
                    $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids); ?>

                    <section class="impact-by-sector">
                      <h2>IMPACT BY SECTOR</h2>
                      <div class="panel-group" id="impacts-accordion" role="tablist" aria-multiselectable="true">
                        <?php 
                          foreach($impacts_by_sector as $sector):
                            $acf_sector_id = 'sector_' . $sector->sector_id;
                            $i = 0;
                            foreach($sector->impacts as $impact): ?>

                              <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="impact-title<?php echo $i; ?>">
                                  <h3 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#impacts-accordion" href="#impact<?php echo $i; ?>" aria-expanded="false" aria-controls="impact<?php echo $i; ?>"><img src="<?php the_field('sector_icon', $acf_sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector->sector_name; ?>" style="background-color:<?php the_field('sector_color', $acf_sector_id); ?>;" /> <?php echo $impact->name; ?></a>
                                  </h3>
                                </div>
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
                <?php $article_id = get_the_ID(); ?>
                <script>var articleId = "<?php echo $article_id; ?>";</script>
                <div class="report-button">
                  
                </div>
              <?php endwhile; endif; ?>
            </article>

          </main>
        </div>
        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>