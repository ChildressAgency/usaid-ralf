<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-9">
          <main class="result">
            <?php 
              if(have_posts()): while(have_posts()): the_post();
                $impact_id = get_the_ID();
                $sectors = get_the_terms($impact_id, 'sectors');
                
                foreach($sectors as $sector):
                  $acf_sector_id = 'sector_' . $sector->term_id; ?>

                  <article>
                    <header class="result-header">
                      <span class="result-type-icon impact"></span>
                      <div class="sector-icon sector-icon-small">
                        <img src="<?php the_field('sector_icon', $sector_id); ?>" class="img-circle img-responsive" alt="<?php echo $sector->name; ?> Sector" style="background-color:<?php the_field('sector_color', $sector_id); ?>;" />
                      </div>
                      <h1><?php the_title(); ?></h1>
                    </header>
                    <section class="result-content">
                      <?php the_content(); ?>
                    </section>
                    <section class="related-activities">
                      <h3>Related Activities</h3>
                      <?php
                        $related_activities = usaidralf_get_related_activities($impact_id);
                        if($related_activities->have_posts()): ?>
                          <ul>
                            <?php while($related_activities->have_posts()): $related_activities->the_post(); ?>
                              <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                            <?php endwhile; wp_reset_postdata(); ?>
                          </ul>
                      <?php else: ?>
                        <p>No related Activities</p>
                      <?php endif; ?>
                    </section>
                  </article>
              
              <?php endforeach; ?>
            <?php endwhile endif; ?>
          </main>
        </div>
        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>