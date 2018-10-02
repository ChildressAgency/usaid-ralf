<div class="media">
  <div class="media-left">
    <span class="result-type-icon activity" data-toggle="tooltip" data-placement="top" title="Activity"></span>
  </div>
  <div class="media-body">
    <h3 class="media-heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php
      $impact_ids = get_field('related_impacts', false, false);
      if(!empty($impact_ids)):
        $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids);

        foreach($impacts_by_sector as $sector):
          $acf_sector_id = '';
          $acf_sub_sector_id = '';
          $sector_name = '';
          $sector_url = '';

          $sector_term = get_term($sector['sector_id'], 'sectors');

          if($sector_term->parent == 0){ //top level sector
            $acf_sector_id = 'sectors_' . $sector_term->term_id;
            $sector_name = $sector_term->name;
            $sector_url = esc_url(get_term_link($sector_term->term_id), 'sectors');
          }
          else{ //sub-sector
            $acf_sector_id = 'sectors_' . $sector_term->parent;
            $acf_sub_sector_id = 'sectors_' . $sector_term->term_id;
            
            $sector_parent = get_term($sector_term->parent, 'sectors');
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
                <?php if($sector_term->parent > 0): ?>
                  <a href="<?php echo esc_url(get_term_link($sector_term->term_id, 'sectors')); ?>" class="sub-sector" data-toggle="tooltip" data-placement="top" title="<?php echo $sector_term->name; ?>">
                    <img src="<?php the_field('sector_icon', $acf_sub_sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector_term->name; ?> Sector" style="background-color:<?php the_field('sector_color', $acf_sub_sector_id); ?>" />
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-sm-8 col-md-9">
              <div class="factor-grid">
              
                <?php foreach($sector['impacts'] as $impact): ?>
                  <div class="grid-item">
                    <a href="<?php echo $impact->impact_link; ?>" class="factor-name"><?php echo $impact->impact_title; ?></a>
                  </div>
                <?php endforeach; ?>

              </div>
            </div>
          </div>
      <?php endforeach; endif; ?>
  </div>
</div>