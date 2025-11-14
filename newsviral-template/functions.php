<?php
/**
 * Theme bootstrap file.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! function_exists( 'newsviral_setup' ) ) {
    /**
     * Register theme supports and menus.
     */
    function newsviral_setup() {
        load_theme_textdomain( 'newsviral', get_template_directory() . '/languages' );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo', [
            'height'      => 60,
            'width'       => 200,
            'flex-height' => true,
            'flex-width'  => true,
        ] );
        add_theme_support( 'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ] );

        register_nav_menus( [
            'primary'   => __( 'Primary menu', 'newsviral' ),
            'secondary' => __( 'Secondary menu', 'newsviral' ),
            'footer'    => __( 'Footer menu', 'newsviral' ),
        ] );
    }
}
add_action( 'after_setup_theme', 'newsviral_setup' );

if ( ! function_exists( 'newsviral_widgets_init' ) ) {
    /**
     * Register default widget areas.
     */
    function newsviral_widgets_init() {
        register_sidebar( [
            'name'          => __( 'Primary Sidebar', 'newsviral' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Add widgets here to appear in your sidebar.', 'newsviral' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ] );

        register_sidebar( [
            'name'          => __( 'Footer Widgets', 'newsviral' ),
            'id'            => 'footer-1',
            'description'   => __( 'Widgets displayed in the footer columns.', 'newsviral' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ] );
    }
}
add_action( 'widgets_init', 'newsviral_widgets_init' );

if ( ! function_exists( 'newsviral_enqueue_assets' ) ) {
    /**
     * Enqueue theme styles and scripts.
     */
    function newsviral_enqueue_assets() {
        $theme_uri = get_template_directory_uri();
        $theme     = wp_get_theme();
        $version   = $theme->get( 'Version' );

        wp_enqueue_style( 'newsviral-google-fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Roboto&display=swap', [], null );

        $base_style_handle = 'newsviral-style-base';
        wp_enqueue_style( $base_style_handle, $theme_uri . '/assets/css/style.css', [], $version );
        wp_enqueue_style( 'newsviral-widgets', $theme_uri . '/assets/css/widgets.css', [ $base_style_handle ], $version );
        wp_enqueue_style( 'newsviral-color', $theme_uri . '/assets/css/color.css', [ $base_style_handle ], $version );
        wp_enqueue_style( 'newsviral-responsive', $theme_uri . '/assets/css/responsive.css', [ $base_style_handle ], $version );

        wp_enqueue_script( 'newsviral-modernizr', $theme_uri . '/assets/js/vendor/modernizr-3.6.0.min.js', [], '3.6.0', false );
        wp_enqueue_script( 'jquery' );

        $vendor_scripts = [
            'newsviral-popper'          => [ 'file' => 'vendor/popper.min.js', 'deps' => [] ],
            'newsviral-bootstrap'       => [ 'file' => 'vendor/bootstrap.min.js', 'deps' => [ 'jquery', 'newsviral-popper' ] ],
            'newsviral-slicknav'        => [ 'file' => 'vendor/jquery.slicknav.js', 'deps' => [ 'jquery' ] ],
            'newsviral-owl-carousel'    => [ 'file' => 'vendor/owl.carousel.min.js', 'deps' => [ 'jquery' ] ],
            'newsviral-slick'           => [ 'file' => 'vendor/slick.min.js', 'deps' => [ 'jquery' ] ],
            'newsviral-wow'             => [ 'file' => 'vendor/wow.min.js', 'deps' => [] ],
            'newsviral-animated-head'   => [ 'file' => 'vendor/animated.headline.js', 'deps' => [ 'jquery' ] ],
            'newsviral-magnific-popup'  => [ 'file' => 'vendor/jquery.magnific-popup.js', 'deps' => [ 'jquery' ] ],
            'newsviral-ticker'          => [ 'file' => 'vendor/jquery.ticker.js', 'deps' => [ 'jquery' ] ],
            'newsviral-vticker'         => [ 'file' => 'vendor/jquery.vticker-min.js', 'deps' => [ 'jquery' ] ],
            'newsviral-scrollup'        => [ 'file' => 'vendor/jquery.scrollUp.min.js', 'deps' => [ 'jquery' ] ],
            'newsviral-nice-select'     => [ 'file' => 'vendor/jquery.nice-select.min.js', 'deps' => [ 'jquery' ] ],
            'newsviral-sticky'          => [ 'file' => 'vendor/jquery.sticky.js', 'deps' => [ 'jquery' ] ],
            'newsviral-perfect-scroll'  => [ 'file' => 'vendor/perfect-scrollbar.js', 'deps' => [ 'jquery' ] ],
            'newsviral-waypoints'       => [ 'file' => 'vendor/waypoints.min.js', 'deps' => [ 'jquery' ] ],
            'newsviral-counterup'       => [ 'file' => 'vendor/jquery.counterup.min.js', 'deps' => [ 'jquery', 'newsviral-waypoints' ] ],
            'newsviral-theia-sticky'    => [ 'file' => 'vendor/jquery.theia.sticky.js', 'deps' => [ 'jquery' ] ],
        ];

        $main_dependencies = [ 'jquery' ];

        foreach ( $vendor_scripts as $handle => $data ) {
            $deps = isset( $data['deps'] ) ? $data['deps'] : [ 'jquery' ];
            wp_enqueue_script( $handle, $theme_uri . '/assets/js/' . $data['file'], $deps, $version, true );
            $main_dependencies[] = $handle;
        }

        $main_dependencies = array_unique( $main_dependencies );

        wp_enqueue_script( 'newsviral-ionicons-module', 'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js', [], null, true );
        wp_script_add_data( 'newsviral-ionicons-module', 'type', 'module' );

        wp_enqueue_script( 'newsviral-ionicons-legacy', 'https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js', [], null, true );
        wp_script_add_data( 'newsviral-ionicons-legacy', 'nomodule', true );

        wp_enqueue_script( 'newsviral-main', $theme_uri . '/assets/js/main.js', $main_dependencies, $version, true );
    }
}
add_action( 'wp_enqueue_scripts', 'newsviral_enqueue_assets' );
