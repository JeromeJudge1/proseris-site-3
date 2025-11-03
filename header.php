<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
  <div class="site-branding">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title"><?php bloginfo('name'); ?></a>
  </div>
  <nav class="site-nav" role="navigation" aria-label="<?php esc_attr_e('Primary Menu','proseris-converted'); ?>">
    <?php wp_nav_menu([ 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'primary-menu' ]); ?>
  </nav>
</header>
<main class="site-main">
