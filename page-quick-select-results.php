<?php get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <main class="results-list">
          <?php
            if(isset($_POST['factor']) && !empty($_POST['factor'])){
              $impact_tag_ids = $_POST['factor'];
              $impact_tag_ids = array_map('intval', $impact_tag_ids);

              $impact_tag_names = [];
              foreach($impact_tag_ids as $index => $impact_tag){
                $term = get_term_by('id', $impact_tag, 'impact_tags');
                $impact_tag_names[] = $term->name;
              }
              $impact_tag_names = implode(', ', $impact_tag_names);
              echo '<h1>' . sprintf(__('Showing results for "%s"', 'usaidralf'), $impact_tag_names) . '</h1>';

              $paged = get_query_var('paged') ? get_query_var('paged') : 1;
              $factors = new WP_Query(array(
                'post_type' => 'impacts',
                'post_status' => 'publish',
                'paged' => $paged,
                'tax_query' => array(
                  array(
                    'taxonomy' => 'impact_tags',
                    'field' => 'term_id',
                    'terms' => $impact_tag_ids
                  )
                )
              ));

              if($factors->have_posts()): while($factors->have_posts()):
                $factors->the_post();
                $impact_id = get_the_ID(); ?>

                <div class="loop-item">
                  <h2 class="loop-item-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                  </h2>
                  <div class="loop-item-meta">
                    <?php usaidralf_show_article_meta('impacts', $impact_id); ?>
                  </div>
                </div>
              <?php endwhile; else: ?>
                <p><?php _e('Sorry, nothing was found for your selected factors.', 'usaidralf'); ?></p>
              <?php endif; usaidralf_pagination(); wp_reset_postdata();
            }
            else{
              echo '<p>' . __('You did not select any factors.', 'usaidralf') . '</p>';
            } ?>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>