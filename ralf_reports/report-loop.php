<?php
// Exit if accessed directly
if (!defined('ABSPATH')){ exit; }
?>
<article class="ralf-article">
  <header class="result-header">
    <?php 
      $report_article_id = get_the_ID(); 
      if(get_post_type() == 'activities'){
        echo '<span class="result-type-icon activity" data-toggle="tooltip" data-placement="top" title="Activity"></span>';
      }
      else if(get_post_type() == 'impacts'){
        echo '<span class="result-type-icon impact" data-toggle="tooltip" data-placement="top" title="Impact"></span>';
      }
    ?>
    <h1 class="article-heading"><a href="#article_id-<?php echo $report_article_id ?>" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="article_id-<?php echo $report_article_id; ?>" class="collapsed"><?php the_title(); ?></a></h1>
  </header>
  <div id="article_id-<?php echo $report_article_id; ?>" class="collapse-container panel-collapse collapse" role="tab-panel" aria-labelledby="article-heading">
    <section class="result-content">
      <div class="activity-description">
        <p><?php the_content(); ?></p>
      </div>
      <?php if(get_field('conditions')): ?>
        <div class="activity-conditions">
          <h2><?php _e('CONDITIONS', 'ralfreports'); ?></h2>
          <?php the_field('conditions'); ?>
        </div>
      <?php endif; ?>
    </section>

    <?php
      $impact_ids = get_field('related_impacts', false, false);
      if(!empty($impact_ids)):
        $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids); ?>

        <section class="impact-by-sector">
          <h2><?php _e('IMPACT BY SECTOR', 'ralfreports'); ?></h2>
          <div class="panel-group" role="tablist">
            <?php
              foreach($impacts_by_sector as $sector):
                $acf_sector_id = 'sectors_' . $sector['sector_id'];
                foreach($sector['impacts'] as $impact): ?>

                  <div class="panel panel-default">
                    <div class="panel-heading" role="tab">
                      <h3 class="panel-title"><a href="#"><?php echo $impact->impact_title; ?></a></h3>
                    </div>
                    <div class="panel-body">
                      <?php echo $impact->impact_description; ?>
                    </div>
                  </div>

              <?php endforeach; ?>
            <?php endforeach; ?>
          </div>
        </section>
    <?php endif; ?>
  </div><!-- end .collapse-container -->
  <?php echo do_shortcode('[report_button]'); ?>
  
</article>