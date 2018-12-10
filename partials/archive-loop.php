<main class="main">
  <?php if(have_posts()): while(have_posts()): the_post(); ?>
    <header class="result-header">
      <h1><?php the_title(); ?></h1>
    </header>
    <section class="result-content">
      <?php the_content(); ?>
    </section>
  <?php endwhile; else: ?>
    <p><?php _e('Sorry, nothing was found.', 'usaidralf'); ?></p>
  <?php endif; usaidralf_pagination(); ?>
</main>