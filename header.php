<!DOCTYPE html>
<html lang="de">

<head>
    <?php wp_head(); ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>

<body <?php body_class(); ?>>
<header id="nav-main">
    <nav class="navbar navbar-expand-lg navbar-main bg-light mt-1 pt-1 shadow">
        <div class="container">
            <!-- Menu -->
            <?php
            wp_nav_menu( array(
                'theme_location'  => 'main-menu',
                'depth'           => 2,
                'container'       => 'div',
                'container_class' => 'collapse dont-collapse-lg d-lg-block w-100',
                'container_id'    => 'main-menu',
                'menu_class'      => 'navbar-nav justify-content-between',
                'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                'walker'          => new WP_Bootstrap_Navwalker(),
            ) );
            ?>
        </div>
    </nav> 
</header>

<div class="main-content">
    <div id="top"></div>