<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#0A3D2C" />
  <link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/img/favicon.svg' ); ?>" type="image/svg+xml" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="BRALIM home">
      <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.svg' ); ?>" alt="BRALIM logo" class="brand-mark" width="42" height="42" />
      <span class="brand-text">
        <span class="brand-name">BRALIM</span>
        <span class="brand-tag">Ugandan Community · Netherlands</span>
      </span>
    </a>

    <nav class="main-nav" aria-label="Primary">
      <?php bralim_nav_menu( 'primary', 'primaryNav' ); ?>
    </nav>

    <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mobileNav" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>

  <div class="mobile-nav" id="mobileNav" hidden>
    <?php bralim_nav_menu( 'primary', 'mobileNav-list' ); ?>
  </div>
</header>

<main id="main">
