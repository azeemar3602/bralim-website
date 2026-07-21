<?php get_header(); ?>

<section class="page-hero">
  <div class="hero-bg"></div>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span>/</span> <span>Events</span></nav>
    <h1>Events</h1>
    <p>From cultural celebrations to networking evenings, there's always something happening at BRALIM. Find an event and join us.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ( have_posts() ) : ?>
      <div class="grid" style="gap:16px">
        <?php while ( have_posts() ) : the_post();
          $edate = get_post_meta( get_the_ID(), 'event_date', true );
          $eloc  = get_post_meta( get_the_ID(), 'event_location', true );
          $day = $month = '';
          if ( $edate ) {
            $ts = strtotime( $edate );
            $day = date( 'd', $ts );
            $month = strtoupper( date( 'M', $ts ) );
          }
        ?>
          <div class="event-row">
            <div class="event-date"><div class="d"><?php echo esc_html( $day ?: '–' ); ?></div><div class="m"><?php echo esc_html( $month ); ?></div></div>
            <div class="event-info">
              <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p class="where">📍 <?php echo esc_html( $eloc ); ?></p>
            </div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-gold">Register</a>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else : ?>
      <p>No upcoming events right now — check back soon.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
