<?php 

/**
 * TODO remove Registers Menu 
 */
function register_menus() {
    register_nav_menus(
        array(
            'header-menu' => __( 'Header Menu' ),
            'footer-menu-1' => __( 'Footer Menu One' ),
            'footer-menu-2' => __( 'Footer Menu Two' ),
        )
    );
}
add_action( 'init', 'register_menus' );
