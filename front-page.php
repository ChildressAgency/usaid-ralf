<?php get_header(); ?>
  <?php if(have_rows('hero_carousel')): ?>
    <div class="hero hp-hero">
      <div id="hp-hero-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
          <?php $i=0; while(have_rows('hero_carousel')): the_row(); ?>

            <?php if(get_field('slide_image')): ?>
              <div class="item<?php if($i==0){ echo ' active'; } ?>" style="background-image:url(<?php the_sub_field('slide_image'); ?>);">
                <div class="slide-caption-wrapper">
                  <div class="slide-caption">
                    <h1><?php the_sub_field('slide_title'); ?></h1>
                    <?php the_sub_field('slide_caption'); ?>
                    <a href="<?php esc_url(get_term_link(get_sub_field('sector_link'))); ?>" class="btn-main">View Impacts</a>
                  </div>
                </div>
              </div>
            <?php $i++; endif; ?>

          <?php endwhile; ?>
        </div>

        <ol class="carousel-indicators">
          <?php for($c=0; $c<$i; $c++): ?>
            <li data-target="#hp-hero-carousel" data-slide-to="<?php echo $c; ?>"<?php if($c==0){ echo ' class="active"'; } ?>></li>
          <?php endfor; ?>
        </ol>
      </div>
    </div>
  <?php endif; ?>
  <div class="search-bar">
    <div class="container">
      <p>Know what you're looking for? Use our general search bar</p>
      <form class="form-inline" action="/" method="get">
        <div class="form-group">
          <label for="search-field" class="sr-only">Search</label>
          <div class="input-group">
            <input type="text" id="search-field" name="s" class="form-control" />
            <div class="input-group-addon">
              <i class="search-icon"></i>
            </div>
          </div>
        </div>
        <input type="submit" class="btn-main" value="Search" />
      </form>
    </div>
  </div>
  <section id="sectors">
    <div class="container">
      <header class="section-header">
        <h2>Sectors</h2>
        <p>Browse our general twelve sectors by selecting one of the icons below.</p>
      </header>
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-agriculture.png" class="img-circle img-responsive center-block" alt="Agriculture Sector" style="background-color:#8cc63f;" />
            <h3>Agriculture</h3>
          </a>
        </div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-construction.png" class="img-circle img-responsive center-block" alt="Construction Sector" style="background-color:#f7931e;" />
            <h3>Construction</h3>
          </a>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-environment.png" class="img-circle img-responsive center-block" alt="Environmental Sector" style="background-color:#009245;" />
            <h3>Environmental</h3>
          </a>
        </div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-health.png" class="img-circle img-responsive center-block" alt="Health Sector" style="background-color:#c1272d;" />
            <h3>Health</h3>
          </a>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="clearfix hidden-xs hidden-sm"></div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-agriculture.png" class="img-circle img-responsive center-block" alt="Agriculture Sector" style="background-color:#ed7c31;" />
            <h3>Agriculture</h3>
          </a>
        </div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-construction.png" class="img-circle img-responsive center-block" alt="Construction Sector" style="background-color:#ed6a56;" />
            <h3>Construction</h3>
          </a>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-environment.png" class="img-circle img-responsive center-block" alt="Environmental Sector" style="background-color:#6a0d69;" />
            <h3>Environmental</h3>
          </a>
        </div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-health.png" class="img-circle img-responsive center-block" alt="Health Sector" style="background-color:#a2a89e;" />
            <h3>Health</h3>
          </a>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="clearfix hidden-xs hidden-sm"></div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-agriculture.png" class="img-circle img-responsive center-block" alt="Agriculture Sector" style="background-color:#b55232;" />
            <h3>Agriculture</h3>
          </a>
        </div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-construction.png" class="img-circle img-responsive center-block" alt="Construction Sector" style="background-color:#f8db94;" />
            <h3>Construction</h3>
          </a>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-environment.png" class="img-circle img-responsive center-block" alt="Environmental Sector" style="background-color:#00bbd6;" />
            <h3>Environmental</h3>
          </a>
        </div>
        <div class="col-sm-6 col-md-3">
          <a class="sector-icon">
            <img src="images/icon-health.png" class="img-circle img-responsive center-block" alt="Health Sector" style="background-color:#577483;" />
            <h3>Health</h3>
          </a>
        </div>        
      </div>
    </div>
  </section>
  <section id="quick-select">
    <div class="container">
      <header class="section-header">
        <h2>Quick Select</h2>
        <p>Select up to seven key factors associated with your project to view a list of activities and impacts that are relevant to your selected points</p>
      </header>
      <form action="search-page" method="get" class="factor-grid">
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Forest" /><span>Forest</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Water" /><span>Water</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="River" /><span>River</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Climate Risk" /><span>Climate Risk</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Soil Erosion" /><span>Soil Erosion</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Chemical Storage" /><span>Chemical Storage</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Deforestation" /><span>Deforestation</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Salinity" /><span>Salinity</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Waste Water" /><span>Waste Water</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Exotic Species" /><span>Exotic Species</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Land Degradation" /><span>Land Degradation</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Water Table" /><span>Water Table</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Poor Extension Policies" /><span>Poor Extension Policies</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Soil Compaction" /><span>Soil Compaction</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Biodiversity" /><span>Biodiversity</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="School" /><span>School</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Over Irrigation" /><span>Over Irrigation</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Carcass Waste" /><span>Carcass Waste</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Air Pollution" /><span>Air Pollution</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Vet Pharma" /><span>Vet Pharma</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Bridge" /><span>Bridge</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Desert" /><span>Desert</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Disease Outbreak" /><span>Disease Outbreak</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Water Pollution" /><span>Water Pollution</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Soil Fertility" /><span>Soil Fertility</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Vehicles" /><span>Vehicles</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Road" /><span>Road</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Plumbing" /><span>Plumbing</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Human Health" /><span>Human Health</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Food Storage" /><span>Food Storage</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Farm" /><span>Farm</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Animal Husbandry">Animal Husbandry</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Pest Management" /><span>Pest Management</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="LiveStock" /><span>LiveStock</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Shallow Well" /><span>Shallow Well</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Nutrition" /><span>Nutrition</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Partnership" /><span>Partnership</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Electricity" /><span>Electricity</span></label>
        </div>
        <div class="grid-item">
          <label class="factor-name"><input type="checkbox" name="factor" value="Government" /><span>Government</span></label>
        </div>
      </form>
      <div class="clearfix"></div>
      <div class="btns-inline">
        <a href="#" class="btn-alt">Load More</a>
        <input type="submit" class="btn-main" value="Search" />
      </div>
    </div>
  </section>
<?php get_footer(); ?>