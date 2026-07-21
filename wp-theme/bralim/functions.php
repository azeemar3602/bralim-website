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
    wp_enqueue_style( 'bralim-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap', array(), null );
    wp_enqueue_style( 'bralim-style', get_template_directory_uri() . '/assets/css/site.css', array(), '1.0' );
    wp_enqueue_script( 'bralim-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0', true );
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
}
add_action( 'init', 'bralim_register_event_cpt' );

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
