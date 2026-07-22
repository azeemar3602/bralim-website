</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <a class="brand brand--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.svg' ); ?>" alt="BRALIM logo" width="40" height="40" />
        <span class="brand-name">BRALIM</span>
      </a>
      <p>Bringing together Ugandans living in the Netherlands — to belong, to preserve our heritage, and to thrive together.</p>
      <div class="social">
        <a href="#" aria-label="Facebook" class="social-link">Facebook</a>
        <a href="#" aria-label="Instagram" class="social-link">Instagram</a>
        <a href="#" aria-label="LinkedIn" class="social-link">LinkedIn</a>
        <a href="#" aria-label="YouTube" class="social-link">YouTube</a>
      </div>
    </div>

    <div class="footer-col">
      <h3>Explore</h3>
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About Us</a></li>
        <li><a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>">Our Programs</a></li>
        <li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Events</a></li>
        <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">News</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h3>Take Part</h3>
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/membership/' ) ); ?>">Become a Member</a></li>
        <li><a href="<?php echo esc_url( home_url( '/get-involved/' ) ); ?>">Volunteer</a></li>
        <li><a href="<?php echo esc_url( home_url( '/get-involved/' ) ); ?>">Partner With Us</a></li>
        <li><a href="<?php echo esc_url( home_url( '/donate/' ) ); ?>">Donate</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h3>Contact</h3>
      <ul class="footer-contact">
        <li><a href="mailto:info@bralim.org">info@bralim.org</a></li>
        <li>+31 (0)00 000 0000</li>
        <li>Address placeholder, Netherlands</li>
      </ul>
    </div>

    <div class="footer-col footer-news">
      <h3>Newsletter</h3>
      <p>Community news, events and stories — straight to your inbox.</p>
      <form class="newsletter" data-form="newsletter" data-endpoint="/wp-json/bralim/v1/newsletter" action="#" method="post" novalidate>
        <div class="hp-field" aria-hidden="true"><label for="hp_web3">Website</label><input type="text" id="hp_web3" name="hp_web" tabindex="-1" autocomplete="off" /></div>
        <label class="sr-only" for="footerEmail">Email address</label>
        <input id="footerEmail" name="email" type="email" placeholder="you@email.com" required />
        <button class="btn btn-gold" type="submit">Subscribe</button>
        <p class="form-note" data-note></p>
      </form>
    </div>
  </div>

  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> BRALIM. All rights reserved.</p>
      <ul class="legal-links">
        <li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy Policy</a></li>
        <li><a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>">Cookie Policy</a></li>
        <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
      </ul>
    </div>
  </div>
</footer>

<div class="cookie-banner" id="cookieBanner" hidden>
  <p>We use essential cookies to make BRALIM work. With your consent we also use analytics cookies to improve the site. See our <a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>">Cookie Policy</a>.</p>
  <div class="cookie-actions">
    <button class="btn btn-ghost" data-cookie="decline">Decline</button>
    <button class="btn btn-gold" data-cookie="accept">Accept</button>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
