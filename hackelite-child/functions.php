<?php
/**
 * InJob-Child functions and definitions
 *
 * @package InJob
 */

add_action( 'after_setup_theme', 'inwave_child_theme_setup' );

function inwave_child_theme_setup() {
    load_child_theme_textdomain( 'injob', get_stylesheet_directory() . '/languages' );
}