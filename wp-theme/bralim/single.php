<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<section class="page-hero">
  <div class="hero-bg"></div>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span>/</span> <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">News</a> <span>/</span> <span><?php the_title(); ?></span></nav>
    <h1><?php the_title(); ?></h1>
    <p class="meta" style="color:#cfe4da"><?php echo esc_html( get_the_date() ); ?></p>
  </div>
</section>

<section class="section">
  <div class="container prose" style="max-width:820px">
    <?php if ( has_post_thumbnail() ) : ?>
      <div style="margin-bottom:28px"><?php the_post_thumbnail( 'large' ); ?></div>
    <?php endif; ?>
    <?php the_content(); ?>
  </div>
</section>
<?php endwhile; ?>

<?php get_footer(); ?>
