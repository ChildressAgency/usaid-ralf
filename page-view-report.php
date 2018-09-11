<?php get_header(); ?>
<div class="page-content">
  <div class="container">
    <main class="results-list">
      <?php 
        if(have_posts()){
          while(have_posts()){
            the_post(); 
            the_content(); 
          }
        }
      ?>
    </main>
  </div>
</div>
<?php get_footer(); ?>