<?php
/**
 * Proseris Converted functions and definitions
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme setup
add_action('after_setup_theme', function() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  register_nav_menus([
    'primary' => __('Primary Menu', 'proseris-converted'),
  ]);
});

// Enqueue assets found during conversion
add_action('wp_enqueue_scripts', function() {
  $theme_uri = get_template_directory_uri();
  wp_enqueue_style('conv-style-css', $theme_uri . '/assets/css/style.css', [], null);
  wp_enqueue_style('conv-app-css', $theme_uri . '/assets/css/app.css', [], null);
  wp_enqueue_script('conv-navigation-js', $theme_uri . '/assets/js/navigation.js', [], null, true);
  wp_enqueue_script('conv-app-js', $theme_uri . '/assets/js/app.js', [], null, true);
});

// On activation: create pages matching detected HTML files, build Primary menu, and set front page
add_action('after_switch_theme', function() {
  $pages = [
    ['slug' => 'index', 'title' => 'Home'],
    ['slug' => 'about', 'title' => 'About'],
    ['slug' => 'case-studies', 'title' => 'Case Studies'],
    ['slug' => 'login', 'title' => 'Login'],
    ['slug' => 'news', 'title' => 'News'],
    ['slug' => 'resources', 'title' => 'Resources'],
    ['slug' => 'request-demo', 'title' => 'Request Demo'],
    ['slug' => 'webinars', 'title' => 'Webinars'],
    ['slug' => 'how-it-works', 'title' => 'How It Works'],
    ['slug' => 'use-cases', 'title' => 'Use Cases'],
    ['slug' => 'contact-sales', 'title' => 'Contact Sales'],
    ['slug' => 'faq', 'title' => 'Faq'],
  ];

  $created_ids = [];
  foreach ($pages as $p) {
    $existing = get_page_by_path($p['slug']);
    if ($existing) { $created_ids[$p['slug']] = $existing->ID; continue; }
    $id = wp_insert_post([
      'post_title' => $p['title'],
      'post_name'  => $p['slug'],
      'post_status'=> 'publish',
      'post_type'  => 'page',
    ]);
    if (!is_wp_error($id)) { $created_ids[$p['slug']] = $id; }
  }

  // Create Primary menu if none
  $menu_name = 'Primary';
  $menu_id = wp_create_nav_menu($menu_name);
  if (!is_wp_error($menu_id)) {
    // Add items in filename order with 'Home' first when present
    uasort($pages, function($a,$b){
      if (strtolower($a['title'])==='home') return -1;
      if (strtolower($b['title'])==='home') return 1;
      return strcmp($a['title'], $b['title']);
    });
    foreach ($pages as $p) {
      if (!isset($created_ids[$p['slug']])) continue;
      wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title' => $p['title'],
        'menu-item-object'=> 'page',
        'menu-item-object-id' => $created_ids[$p['slug']],
        'menu-item-type'  => 'post_type',
        'menu-item-status'=> 'publish'
      ]);
    }
    $locations = get_theme_mod('nav_menu_locations');
    if (!is_array($locations)) $locations = [];
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
  }

  // Set static front page if we have a Home page or the first detected
  $front_id = null;
  foreach ($pages as $p) {
    if (strtolower($p['title'])==='home' && isset($created_ids[$p['slug']])) { $front_id = $created_ids[$p['slug']]; break; }
  }
  if (!$front_id && !empty($pages)) {
    $first = $pages[0];
    if (isset($created_ids[$first['slug']])) $front_id = $created_ids[$first['slug']];
  }
  if ($front_id) {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $front_id);
  }
});
