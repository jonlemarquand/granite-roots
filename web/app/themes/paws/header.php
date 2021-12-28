<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<title>
		<?php 
			if(is_front_page() || is_home()){
				echo get_bloginfo('name');
			} else{
				echo wp_title('');
			}
		?>
	</title>
    <?php wp_head(); ?>
	<meta name="theme-color" content="#3367D6"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body <?php body_class(); ?>>

	<a href="#main-content" class="visually-hidden">Skip to main content</a>

    <?php wp_body_open(); ?>
		<?php if(!is_front_page()) { ?>
    <header class="header" role="banner">
        <div class="header__inner container">
					<a href="/" class="logo">Granite<span>Roots</span></a>

			<?php if (has_nav_menu('header-menu')) { ?>

				<nav class="header__navigation" role="navigation">

					<button class="header__burger js-header-burger">
						<span class="screen-reader-text">Menu</span>
						<span class="burger__line burger__line--1"></span>
						<span class="burger__line burger__line--2"></span>
						<span class="burger__line burger__line--3"></span>
					</button>

					<?php wp_nav_menu(array(
						'theme_location' => 'header-menu',
						'depth' => 0,
						'container' => '',
						'menu_class' => '',
						'menu_id' => 'main-menu',
						'fallback_cb' => false,
					)); ?>
				</nav>

			<?php } ?>

        </div>
    </header>
		<?php } ?>

    <div class="site" id="main-content">
