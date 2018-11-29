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
                echo '<p>Sorry, nothing was found.</p>';
              } usaidralf_pagination();
            ?>
          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>