<?php get_header(); ?>

<section class="container">
  <div class="row my-4">
    <?php 
    while(have_posts()) {
      the_post();?>
      <h2><?php the_title(); ?></h2>
      <?php the_content();
    } ?>
  </div>
</section>

<?php get_footer(); ?>