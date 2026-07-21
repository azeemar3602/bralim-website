<?php get_header(); ?>

<section class="page-hero">
  <div class="hero-bg"></div>
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span>/</span> <span>News</span></nav>
    <h1>News &amp; Stories</h1>
    <p>News articles, community stories, announcements, newsletters and media updates from across our community.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php if ( have_posts() ) : ?>
      <div class="grid grid-3">
        <?php while ( have_posts() ) : the_post(); ?>
          <article class="card post-card">
            <?php if ( has_post_thumbnail() ) : ?>
              <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
            <?php else : ?>
              <a href="<?php the_permalink(); ?>" class="ph ph--16x9"><span class="ph-label">Article image</span></a>
            <?php endif; ?>
            <div class="pc-body">
              <?php foreach ( get_the_category() as $cat ) : ?>
                <span class="tag"><?php echo esc_html( $cat->name ); ?></span>
              <?php endforeach; ?>
              <h3 style="margin-top:12px"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
              <p class="meta"><?php echo esc_html( get_the_date() ); ?></p>
              <a href="<?php the_permalink(); ?>" class="card-link" style="margin-top:auto">Read more</a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
      <div style="margin-top:34px"><?php the_posts_pagination(); ?></div>
    <?php else : ?>
      <p>No news articles yet. Check back soon.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
