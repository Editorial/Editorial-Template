<?php

add_action( 'init', 'register_my_menu' );

function register_my_menu()
{
    register_nav_menu( 'primary-menu', __( 'Primary Menu' ) );
}
?>