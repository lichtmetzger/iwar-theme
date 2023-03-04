<?php
function iwar_features() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support( 'align-wide' );
  add_image_size('iwar_thumb', 800, 800, true);
  add_image_size('iwar_thumb_wide', 800, 450, true);
  add_image_size('iwar_slide', 1920, 1080, true);
  $logo_defaults = array(
      'height'               => 100,
      'width'                => 400,
      'flex-height'          => true,
      'flex-width'           => true,
      'header-text'          => array( 'site-title', 'site-description' ) 
    );
  add_theme_support( 'custom-logo', $logo_defaults );
}
add_action('after_setup_theme', 'iwar_features');

function register_iwar_menus() {
  register_nav_menus(
    array(
      'main-menu'     => __( 'Main Menu' ),
      'footer-left' => __( 'Footer (left)' ),
      'footer-center'    => __( 'Footer (center)' ),
      'footer-right'    => __( 'Footer (right)' ),
      'footer-bottom' => __( 'Footer (bottom)' ),
    )
  );
}
add_action( 'init', 'register_iwar_menus' );
