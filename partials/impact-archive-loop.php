<?php
  $impact_id = get_the_ID();
  //print_r($impact_id);
  $num_activities = usaidralf_get_related_activities($impact_id);
  //var_dump($num_activities);
  if($num_activities->post_count > 0): ?>
    <div class="media">
      <div class="media-left">
        <span class="result-type-icon impact" data-toggle="tooltip" data-placement="top" title="Impact"></span>
      </div>
      <div class="media-body">
        <h3 class="media-heading"><?php the_title(); ?></h3>
        <?php
          $sectors = get_the_terms($impact_id, 'sectors');
          foreach($sectors as $sector): ?>
            <?php $acf_sector_id = 'sectors_' . $sector->term_id; ?> 

                <div class="row">
                  <div class="col-sm-4 col-md-3">
                    <div class="sector-icon" data-toggle="tooltip" data-placement="top" title="<?php echo $sector->name; ?>">
                      <img src="<?php the_field('sector_icon', $acf_sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector->name; ?> Sector" style="background-color:<?php the_field('sector_color', $acf_sector_id); ?>;" />
                    </div>
                  </div>
                  <div class="col-sm-8 col-md-9">
                    <div class="number-activities">
                      <a href="<?php the_permalink(); ?>"><span><?php echo $num_activities->post_count; ?></span>Activities</a>
                    </div>
                  </div>
                </div>
          
        <?php endforeach; ?>
      </div>
    </div>
<?php else: 
  //if(is_page('quick-select-results')){
    if(isset($_POST['factor']) && !empty($_POST['factor'])){
      echo '<div class="media-body" style="padding-top:40px;"><p>No Activities have been associated with the <span style="text-transform:capitalize;">' . get_the_title() . '</span> impact.</p></div>';
    }
    else{
      echo '<p>No factors have been selected.</p>';
    }
  //} ?>
    
<?php endif; ?>
