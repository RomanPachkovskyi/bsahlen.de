<?php
/**
 * BSahlen Child Theme
 *
 * Custom code for bsahlen.de
 * CSS: style.css
 * JS: assets/js/custom.js
 */

// Enqueue styles
add_action('wp_enqueue_scripts', 'bsahlen_enqueue_styles', 20);
function bsahlen_enqueue_styles() {
    // Parent theme
    wp_enqueue_style('finovate-style', get_template_directory_uri() . '/style.css');

    // Child theme (custom CSS)
    wp_enqueue_style('bsahlen-style', get_stylesheet_uri(), array('finovate-style'), '1.0');
}

// Enqueue scripts
add_action('wp_enqueue_scripts', 'bsahlen_enqueue_scripts', 20);
function bsahlen_enqueue_scripts() {
    wp_enqueue_script(
        'bsahlen-js',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array('jquery'),
        '1.0',
        true // Load in footer
    );
}
