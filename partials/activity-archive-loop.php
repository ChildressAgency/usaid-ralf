<div class="media">
  <div class="media-left">
    <span class="result-type-icon activity"></span>
  </div>
  <div class="media-body">
    <h3 class="media-heading"><?php the_title(); ?></h3>
    <?php
      $impact_ids = get_field('related_impacts', false, false);
      if(!empty($impact_ids)){
        $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids);
      }

      foreach($impacts_by_sector as $sector): ?>
        <div class="row">
          <div class="col-sm-4 col-md-3">
            <div class="sector-icon">
              <?php $acf_sector_id = 'sector_' . $sector->sector_id; ?>
              <img src="<?php the_field('sector_icon', $acf_sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector->sector; ?>" style="background-color:<?php the_field('sector_color', $acf_sector_id); ?>;" />
            </div>
          </div>
          <div class="col-sm-8 col-md-9">
            <div class="factor-grid">
              <div class="grid-item">
                <a href="#" class="factor-name">Forest</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Climate Risk</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Deforestation</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Exotic Species</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Poor Extension Policies</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">School</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Vet Pharma</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Water Pollution</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Road</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Food Storage</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Pest Management</a>
              </div>
              <div class="grid-item">
                <a href="#" class="factor-name">Partnership</a>
              </div>
            </div>
          </div>
        </div>
    <?php endforeach; ?>
  </div>
</div>