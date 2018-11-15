<?php get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-9">
          <main class="results-list">
            <?php 
              if(isset($_POST['factor']) && !empty($_POST['factor'])){
                $impact_tag_ids = $_POST['factor'];
                //$impact_tag_ids = implode(', ', $impact_tag_ids);
                $impact_tag_ids = array_map('intval', $impact_tag_ids);
                //var_dump($impact_tag_ids);

                echo '<p>' . __('Showing results for the following tags: ', 'usaidralf');
                $len = count($impact_tag_ids);
                foreach($impact_tag_ids as $index => $impact_tag){
                  $term = get_term_by('id', $impact_tag, 'impact_tags');
                  echo $term->name;
                  if($index < $len - 1){ echo ', '; }
                }
                echo '</p>';

                $factors = new WP_Query(array(
                  'post_type' => array('impacts', 'activities'),
                  'post_status' => 'publish',
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'impact_tags',
                      'field' => 'term_id',
                      //'operator' => 'AND',
                      'terms' => $impact_tag_ids
                    )
                  )
                ));
                //print_r($factors);

                if($factors->have_posts()){
                  while($factors->have_posts()){
                    $factors->the_post();
                    
                    if(get_post_type(get_the_ID()) == 'activities'){
                      get_template_part('partials/activity', 'archive-loop');
                    }
                    else{
                      get_template_part('partials/impact', 'archive-loop');
                    }
                  }
                }
                else{
                  echo '<p>' . __('Sorry, nothing was found.', 'usaidralf') . '</p>';
                }
              }
              else{
                if(have_posts()){
                  while(have_posts()){
                    the_post();
                    if(get_post_type(get_the_ID()) == 'activities'){
                      get_template_part('partials/activity', 'archive-loop');
                    }
                    else{
                      get_template_part('partials/impact', 'archive-loop');
                    }
                  }
                }
                else{
                  echo '<p>' . __('Sorry, nothing was found.', 'usaidralf') . '</p>';
                }
              }
            ?>
          </main>
        </div>
        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>