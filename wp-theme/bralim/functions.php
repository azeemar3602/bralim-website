<?php
function bralim_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'bralim' ),
    ) );
}
add_action( 'after_setup_theme', 'bralim_setup' );

function bralim_assets() {
    $css_path = get_template_directory() . '/assets/css/site.css';
    $js_path  = get_template_directory() . '/assets/js/main.js';
    wp_enqueue_style( 'bralim-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap', array(), null );
    wp_enqueue_style( 'bralim-style', get_template_directory_uri() . '/assets/css/site.css', array(), file_exists( $css_path ) ? filemtime( $css_path ) : '1.0' );
    wp_enqueue_script( 'bralim-main', get_template_directory_uri() . '/assets/js/main.js', array(), file_exists( $js_path ) ? filemtime( $js_path ) : '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'bralim_assets' );

// Our page content is hand-authored full HTML (sections, divs) — don't let wpautop mangle it.
remove_filter( 'the_content', 'wpautop' );

// Events custom post type — reusable template for the Events section.
function bralim_register_event_cpt() {
    register_post_type( 'event', array(
        'labels' => array(
            'name' => 'Events',
            'singular_name' => 'Event',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'events' ),
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'show_in_rest' => true,
    ) );

    register_post_meta( 'event', 'event_date', array(
        'type' => 'string', 'single' => true, 'show_in_rest' => true,
        'auth_callback' => function () { return current_user_can( 'edit_posts' ); },
    ) );
    register_post_meta( 'event', 'event_location', array(
        'type' => 'string', 'single' => true, 'show_in_rest' => true,
        'auth_callback' => function () { return current_user_can( 'edit_posts' ); },
    ) );
}
add_action( 'init', 'bralim_register_event_cpt' );

/* ---------- SEO: meta description + Open Graph tags ---------- */
function bralim_seo_meta() {
    $description = get_bloginfo( 'description' );
    $title = wp_get_document_title();
    $url = is_singular() ? get_permalink() : home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );

    if ( is_singular() ) {
        global $post;
        if ( $post && has_excerpt( $post ) ) {
            $description = get_the_excerpt( $post );
        }
    }
    $description = esc_attr( wp_strip_all_tags( $description ) );
    echo "\n" . '<meta name="description" content="' . $description . '" />' . "\n";
    echo '<meta property="og:type" content="website" />' . "\n";
    echo '<meta property="og:site_name" content="BRALIM" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . $description . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    echo '<meta name="twitter:card" content="summary" />' . "\n";
}
add_action( 'wp_head', 'bralim_seo_meta', 1 );

/* ---------- Forms: contact / membership / newsletter -> email, with honeypot spam guard ---------- */
function bralim_form_is_spam( $params ) {
    if ( ! empty( $params['hp_web'] ) ) return true; // honeypot filled = bot
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $key = 'bralim_form_' . md5( $ip );
    if ( get_transient( $key ) ) return true; // rate-limited
    set_transient( $key, 1, 20 ); // 20s cooldown per IP
    return false;
}

function bralim_rest_contact( $request ) {
    $p = $request->get_params();
    if ( bralim_form_is_spam( $p ) ) {
        return new WP_REST_Response( array( 'ok' => false ), 200 ); // silently drop for bots
    }
    $name = sanitize_text_field( $p['name'] ?? '' );
    $email = sanitize_email( $p['email'] ?? '' );
    $subject = sanitize_text_field( $p['subject'] ?? 'Website contact form' );
    $message = sanitize_textarea_field( $p['message'] ?? '' );
    if ( ! $name || ! is_email( $email ) || ! $message ) {
        return new WP_REST_Response( array( 'ok' => false, 'error' => 'missing_fields' ), 400 );
    }
    $body = "New contact form submission from bralim.org\n\n" .
        "Name: $name\nEmail: $email\nSubject: $subject\n\nMessage:\n$message";
    wp_mail( get_option( 'admin_email' ), '[BRALIM Contact] ' . $subject, $body, array( 'Reply-To: ' . $email ) );
    return new WP_REST_Response( array( 'ok' => true ), 200 );
}

function bralim_rest_membership( $request ) {
    $p = $request->get_params();
    if ( bralim_form_is_spam( $p ) ) {
        return new WP_REST_Response( array( 'ok' => false ), 200 );
    }
    $name = sanitize_text_field( $p['fullName'] ?? '' );
    $email = sanitize_email( $p['email'] ?? '' );
    $phone = sanitize_text_field( $p['phone'] ?? '' );
    $city = sanitize_text_field( $p['city'] ?? '' );
    $occupation = sanitize_text_field( $p['occupation'] ?? '' );
    $interests = isset( $p['interests'] ) ? ( is_array( $p['interests'] ) ? implode( ', ', array_map( 'sanitize_text_field', $p['interests'] ) ) : sanitize_text_field( $p['interests'] ) ) : '';
    $volunteer = ! empty( $p['volunteer'] ) ? 'Yes' : 'No';
    if ( ! $name || ! is_email( $email ) || ! $phone || ! $city ) {
        return new WP_REST_Response( array( 'ok' => false, 'error' => 'missing_fields' ), 400 );
    }
    $body = "New BRALIM membership application\n\n" .
        "Name: $name\nEmail: $email\nPhone: $phone\nCity: $city\nOccupation: $occupation\n" .
        "Interests: $interests\nInterested in volunteering: $volunteer";
    wp_mail( get_option( 'admin_email' ), '[BRALIM Membership] ' . $name, $body, array( 'Reply-To: ' . $email ) );
    return new WP_REST_Response( array( 'ok' => true ), 200 );
}

function bralim_rest_newsletter( $request ) {
    $p = $request->get_params();
    if ( bralim_form_is_spam( $p ) ) {
        return new WP_REST_Response( array( 'ok' => false ), 200 );
    }
    $email = sanitize_email( $p['email'] ?? '' );
    if ( ! is_email( $email ) ) {
        return new WP_REST_Response( array( 'ok' => false, 'error' => 'invalid_email' ), 400 );
    }
    wp_mail( get_option( 'admin_email' ), '[BRALIM Newsletter] New subscriber', "New newsletter signup: $email" );
    return new WP_REST_Response( array( 'ok' => true ), 200 );
}

function bralim_register_form_routes() {
    register_rest_route( 'bralim/v1', '/contact', array( 'methods' => 'POST', 'callback' => 'bralim_rest_contact', 'permission_callback' => '__return_true' ) );
    register_rest_route( 'bralim/v1', '/membership', array( 'methods' => 'POST', 'callback' => 'bralim_rest_membership', 'permission_callback' => '__return_true' ) );
    register_rest_route( 'bralim/v1', '/newsletter', array( 'methods' => 'POST', 'callback' => 'bralim_rest_newsletter', 'permission_callback' => '__return_true' ) );
}
add_action( 'rest_api_init', 'bralim_register_form_routes' );

function bralim_nav_menu( $location, $id ) {
    wp_nav_menu( array(
        'theme_location' => $location,
        'menu_id'        => $id,
        'container'      => false,
        'menu_class'     => 'nav-list',
        'fallback_cb'    => 'bralim_nav_fallback',
    ) );
}

function bralim_nav_fallback() {
    $items = array(
        array( 'Home', home_url( '/' ) ),
        array( 'About Us', home_url( '/about/' ) ),
        array( 'Programs', home_url( '/programs/' ) ),
        array( 'Membership', home_url( '/membership/' ) ),
        array( 'Events', home_url( '/events/' ) ),
        array( 'News', home_url( '/news/' ) ),
        array( 'Get Involved', home_url( '/get-involved/' ) ),
        array( 'Contact', home_url( '/contact/' ) ),
        array( 'Donate', home_url( '/donate/' ), 'nav-cta' ),
    );
    echo '<ul class="nav-list">';
    foreach ( $items as $item ) {
        $cls = isset( $item[2] ) ? ' class="' . esc_attr( $item[2] ) . '"' : '';
        echo '<li><a href="' . esc_url( $item[1] ) . '"' . $cls . '>' . esc_html( $item[0] ) . '</a></li>';
    }
    echo '</ul>';
}
