<div class="media">
  <div class="media-left">
    <span class="result-type-icon impact"></span>
  </div>
  <div class="media-body">
    <h3 class="media-heading"><?php the_title(); ?></h3>
    <?php
      $impact_id = get_the_ID();
      $sectors = get_the_terms($impact_id, 'sectors');
      foreach($sectors as $sector): ?>
        <?php $acf_sector_id = 'sector_' . $sector->term_id; ?>
        <div class="row">
          <div class="col-sm-4 col-md-3">
            <div class="sector-icon">
              <img src="<?php the_field('sector_icon', $sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector->name; ?> Sector" style="background-color:<?php the_field('sector_color', $sector_id); ?>;" />
            </div>
          </div>
          <div class="col-sm-8 col-md-9">
            <div class="number-activities">
              <?php $num_activities = usaidralf_get_related_activities($impact_id); ?>
              <a href="<?php the_permalink(); ?>"><span><?php echo $num_activities->post_count; ?></span>Activities</a>
            </div>
          </div>
        </div>
      
    <?php endforeach; ?>
  </div>
</div>
