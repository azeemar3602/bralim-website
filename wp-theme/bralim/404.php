<?php get_header(); ?>

<section class="section" style="text-align:center;padding:120px 0">
  <div class="container" style="max-width:640px">
    <div style="font-family:var(--font-head);font-weight:800;font-size:6rem;color:var(--green-100);line-height:1">404</div>
    <h1 style="margin-top:-10px">This page wandered off</h1>
    <p class="lead" style="margin:16px auto 28px">The page you're looking for doesn't exist or has moved. Let's get you back home.</p>
    <div class="hero-cta" style="justify-content:center">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-gold btn-lg">Back to Home</a>
      <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline btn-lg">Contact Us</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
