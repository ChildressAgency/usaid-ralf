<?php get_header(); ?>
  <?php if(have_rows('hero_carousel')): ?>
    <div class="hero hp-hero">
      <div id="hp-hero-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner" role="listbox">
          <?php $i=0; while(have_rows('hero_carousel')): the_row(); ?>

            <?php if(get_sub_field('slide_image')): ?>
              <div class="item<?php if($i==0){ echo ' active'; } ?>" style="background-image:url(<?php the_sub_field('slide_image'); ?>);">
                <div class="slide-caption-wrapper">
                  <div class="slide-caption">
                    <h1><?php the_sub_field('slide_title'); ?></h1>
                    <?php the_sub_field('slide_caption'); ?>
                    <a href="<?php echo esc_url(get_term_link(get_sub_field('sector_link'))); ?>" class="btn-main"><?php _e('View Impacts', 'usaidralf'); ?></a>
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
      <form class="form-inline" action="<?php echo home_url(); ?>" method="get">
        <div class="form-group">
          <label for="search-field" class="sr-only"><?php _e('Search', 'usaidralf'); ?></label>
          <div class="input-group">
            <input type="text" id="search-field" name="s" class="form-control" />
            <div class="input-group-addon">
              <i class="search-icon"></i>
            </div>
          </div>
        </div>
        <input type="submit" class="btn-main" value="<?php _e('Search', 'usaidralf'); ?>" />
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
            $acf_sector_id = 'sectors_' . $sector->term_id;
            $sector_icon_url = get_field('sector_icon', $acf_sector_id);
            $sector_color = get_field('sector_color', $acf_sector_id);

            if($sc%2==0){ echo '<div class="clearfix visible-sm-block"></div>'; }
            if($sc%4==0){ echo '<div class="clearfix hidden-xs hidden-sm"></div>'; } ?>

            <div class="col-sm-6 col-md-3">
              <a href="<?php echo esc_url(get_term_link($sector->term_id, 'sectors')); ?>" class="sector-icon">
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

      <form action="<?php echo home_url('quick-select-results'); ?>" method="post">
        <div class="factor-grid terms-grid">
          <?php 
            $impact_tags = get_terms(array(
              'taxonomy' => 'impact_tags',
              'count' => true,
              'number' => 40,
              'hide_empty' => true,
              'orderby' => 'count',
              'order' => 'DESC'
            ));

            foreach($impact_tags as $impact_tag): ?>
              <div class="grid-item">
                <label class="factor-name">
                  <input type="checkbox" name="factor[]" value="<?php echo $impact_tag->term_id; ?>" />
                  <span><?php echo $impact_tag->name; ?></span>
                </label>
              </div>
          <?php endforeach; ?>
        </div>
        <div class="clearfix"></div>
        <div class="btns-inline">
          <a href="#" class="btn-alt"><?php _e('Load More', 'usaidralf'); ?></a>
          <input type="submit" class="btn-main" value="<?php _e('Search', 'usaidralf'); ?>" />
        </div>
      </form>
    </div>
  </section>

  <section id="common-search-terms">
    <div class="container">
      <header class="section-header">
        <h2><?php the_field('common_search_terms_section_title'); ?></h2>
        <?php the_field('common_search_terms_section_intro'); ?>
      </header>

      <div class="terms-grid">
        <?php
          global $wpdb;
          $common_search_terms = $wpdb->get_results("
            SELECT query, MAX(hits) as hits
            FROM wp_swp_log
            WHERE hits > 0
            GROUP BY query
            ORDER BY COUNT(query) DESC, hits DESC
            LIMIT 20");

          $filter_chars = '()^;<>/\'"!';
          foreach($common_search_terms as $search_term){
            if(strpbrk($search_term->query, $filter_chars) == false){
              echo '<div class="grid-item">';
              echo '<a href="' . esc_url(add_query_arg('s', $search_term->query, home_url())) . '" class="search-term">' . $search_term->query . ' <span>(' . $search_term->hits . ' ' . __('results', 'usaidralf') . ')</a>';
              echo '</div>';
            }
          }
        ?>
      </div>
    </div>
  </section>
<?php get_footer(); ?>