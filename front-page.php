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
      <p><?php the_field('search_bar_text'); ?></p>
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
        <h2><?php the_field('sectors_section_title'); ?></h2>
        <?php the_field('sectors_section_intro'); ?>
      </header>
      <div class="row">
        <?php
          $sc = 0;
          $sectors = get_terms(array('taxonomy' => 'sectors', 'parent' => 0, 'orderby' => 'name'));
          foreach($sectors as $sector):
            $acf_sector_id = 'sector_' . $sector->id;
            $sector_icon_url = get_field('sector_icon', $acf_sector_id);
            $sector_color = get_field('sector_color', $acf_sector_id);

            if($sc%2==0){ echo '<div class="clearfix visible-sm-block"></div>'; }
            if($sc%4==0){ echo '<div class="clearfix hidden-xs hidden-sm"></div>'; } ?>

            <div class="col-sm-6 col-md-3">
              <a href="<?php echo esc_url(get_term_link($sector->id)); ?>" class="sector-icon">
                <img src="<?php echo $sector_icon_url; ?>" class="img-circle img-responsive center-block" alt="Agriculture Sector" style="background-color:<?php echo $sector_color; ?>;" />
                <h3><?php echo $sector->name; ?></h3>
              </a>
            </div>

        <?php $sc++; endforeach; ?>
      </div>
    </div>
  </section>

  <section id="quick-select">
    <div class="container">
      <header class="section-header">
        <h2><?php the_field('quick_select_section_title'); ?></h2>
        <?php the_field('quick_select_section_intro'); ?>
      </header>

      <form action="<?php echo home_url(); ?>" method="get" class="factor-grid">
        <?php 
          $impact_tags = get_terms(array(
            'taxonomy' => 'impact_tags',
            'count' => true,
            'number' => 40,
            'orderby' => 'count',
            'order' => 'DESC'
          ));

          foreach($impact_tags as $impact_tag): ?>
            <div class="grid-item">
              <label class="factor-name">
                <input type="checkbox" name="factor[]" value="<?php echo $impact_tag->slug; ?>" />
                <span><?php echo $impact_tag->name; ?></span>
              </label>
            </div>
        <?php endforeach; ?>
      </form>
      <div class="clearfix"></div>
      <div class="btns-inline">
        <a href="#" class="btn-alt">Load More</a>
        <input type="submit" class="btn-main" value="Search" />
      </div>
    </div>
  </section>
<?php get_footer(); ?>