<?php
  $impact_id = get_the_ID();
  //print_r($impact_id);
  $num_activities = usaidralf_get_related_activities($impact_id);
  //var_dump($num_activities);
  if($num_activities->post_count > 0): ?>
    <div class="media<?php if(has_term('', 'priority_keywords')){ echo ' keyword'; } ?>">
      <div class="media-left">
        <span class="result-type-icon impact" data-toggle="tooltip" data-placement="top" title="Impact"></span>
      </div>
      <div class="media-body">
        <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <?php
          $sectors = get_the_terms($impact_id, 'sectors');
          if($sectors):
          foreach($sectors as $sector): 
              $acf_sector_id = '';
              $acf_sub_sector_id = '';
              $sector_name = '';
              $sector_url = '';

              if($sector->parent == 0){ //top level sector
                $acf_sector_id = 'sectors_' . $sector->term_id;
                $sector_name = $sector->name;
                $sector_url = esc_url(get_term_link($sector->term_id), 'sectors');
              }
              else{ //sub-sector
                $acf_sector_id = 'sectors_' . $sector->parent;
                $acf_sub_sector_id = 'sectors_' . $sector->term_id;
                
                $sector_parent = get_term($sector->parent, 'sectors');
                $sector_name = $sector_parent->name;
                $sector_url = esc_url(get_term_link($sector_parent->term_id), 'sectors');
              }
            ?>
                <div class="row">
                  <div class="col-sm-4 col-md-3">
                    <div class="sector-icon">
                      <a href="<?php echo $sector_url; ?>" class="parent-sector" data-toggle="tooltip" data-placement="top" title="<?php echo $sector_name; ?>">
                        <img src="<?php the_field('sector_icon', $acf_sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector_name; ?> Sector" style="background-color:<?php the_field('sector_color', $acf_sector_id); ?>;" />
                      </a>
                      <?php if($sector->parent > 0): ?>
                        <a href="<?php echo esc_url(get_term_link($sector->term_id, 'sectors')); ?>" class="sub-sector" data-toggle="tooltip" data-placement="top" title="<?php echo $sector->name; ?>">
                          <img src="<?php the_field('sector_icon', $acf_sub_sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector->name; ?> Sector" style="background-color:<?php the_field('sector_color', $acf_sub_sector_id); ?>" />
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="col-sm-8 col-md-9">
                    <div class="number-activities">
                      <a href="<?php the_permalink(); ?>"><span><?php echo $num_activities->post_count; ?></span>Activities</a>
                    </div>
                  </div>
                </div>
          
        <?php endforeach; else: ?>
         <p>No impacts are associated with this activity.</p>
        <?php endif; ?>
      </div>
    </div>
<?php else: 
  //if(is_page('quick-select-results')){
    if(isset($_POST['factor']) && !empty($_POST['factor'])){
      echo '<div class="media-body" style="padding-top:40px;"><p>No Activities have been associated with the <span style="text-transform:capitalize;">' . get_the_title() . '</span> impact.</p></div>';
    }
    else{
      //echo '<p>No factors have been selected.</p>';
    }
  //} ?>
    
<?php endif; ?>
