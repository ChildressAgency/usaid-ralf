<?php get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <main class="main">
          <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <header class="result-header">
              <h1><?php the_title(); ?></h1>
            </header>
            <section class="result-content">
              <?php the_content(); ?>
            </section>
          <?php endwhile; endif; ?>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>