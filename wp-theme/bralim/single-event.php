<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
  $edate = get_post_meta( get_the_ID(), 'event_date', true );
  $eloc  = get_post_meta( get_the_ID(), 'event_location', true );
?>
<section class="page-hero">
  <div class="hero-bg"></div>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span>/</span> <a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Events</a> <span>/</span> <span><?php the_title(); ?></span></nav>
    <h1><?php the_title(); ?></h1>
    <?php if ( $edate || $eloc ) : ?>
      <p style="color:#cfe4da"><?php echo esc_html( $edate ); ?><?php echo ( $edate && $eloc ) ? ' · ' : ''; ?><?php echo esc_html( $eloc ); ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="section">
  <div class="container prose" style="max-width:820px">
    <?php the_content(); ?>
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-gold btn-lg" style="margin-top:10px">Register Interest</a>
  </div>
</section>
<?php endwhile; ?>

<?php get_footer(); ?>
