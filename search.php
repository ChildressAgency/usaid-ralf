<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
        <div class="col-sm-8 col-md-9">
          <main class="results-list">
            <?php $searched_word = get_search_query(); ?>
            <h1><?php printf(__('Search results for "%s"', 'usaidralf'), $searched_word); ?></h1>

            <ul class="nav nav-pills nav-justified" role="tablist">
              <li role="presentation" class="active"><a href="#impacts-activities" aria-controls="impacts-activities" role="tab" data-toggle="tab"><?php _e('Impacts / Activities', 'usaidralf'); ?></a></li>
              <li role="presentation"><a href="#resources" aria-controls="resources" role="tab" data-toggle="tab"><?php _e('Resources', 'usaidralf'); ?></a></li>
            </ul>

            <div class="tab-content">
              <div id="impacts-activities" class="tab-pane fade in active" role="tabpanel">
                <?php
                  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

                  $impacts_activities = new SWP_Query(array(
                    'post_type' => array('impacts', 'activities'),
                    's' => $searched_word,
                    'engine' => 'default',
                    'posts_per_page' => 10,
                    'page' => $paged,
                    'fields' => 'all'
                  ));

                  if(!empty($impacts_activities->posts)): foreach($impacts_activities->posts as $post):
                    setup_postdata($post);
                    $article_id = get_the_ID(); ?>

                    <div class="loop-item">
                      <h2 class="loop-item-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      </h2>
                      <div class="loop-item-meta">
                        <?php 
                          if(has_term($searched_word, 'priority_keywords')){
                            echo '<span class="priority"></span>';
                          }
                          usaidralf_show_article_meta(get_post_type($article_id), $article_id); 
                        ?>
                      </div>
                    </div>
                <?php endforeach; else: ?>
                  <p><?php _e('Sorry, nothing was found.', 'usaidralf'); ?></p>
                <?php endif; usaidralf_pagination(); wp_reset_postdata(); ?>
              </div>

              <div id="resources" class="tab-pane fade" role="tabpanel">
                <?php
                  $resources = new SWP_Query(array(
                    'post_type' => 'resources',
                    's' => $searched_word,
                    'engine' => 'default',
                    'posts_per_page' => -1,
                    'fields' => 'all'
                  ));

                  if(!empty($resources->posts)): foreach($resources->posts as $post):
                    setup_postdata($post);
                    $resource_id = get_the_ID(); ?>

                    <div class="loop-item">
                      <h2 class="loop-item-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                      </h2>
                      <div class="loop-item-meta">
                        <?php 
                          if(has_term($searched_word, 'priority_keywords')){
                            echo '<span class="priority"></span>';
                          }
                          usaidralf_show_article_meta('resources', $resource_id); 
                        ?>
                      </div>
                    </div>
                <?php endforeach; else: ?>
                  <p><?php _e('Sorry, no resources found.', 'usaidralf'); ?></p>
                <?php endif; wp_reset_postdata(); ?>
              </div>
            </div>

          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>