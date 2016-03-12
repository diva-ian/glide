<?php

/**
 * Plugin Name: Glide
 * Description: Simple integration of Glide with WordPress
 * Author: Fredrik Forsmo
 * Author URI: https://frozzare.com
 * Version: 1.0.0
 * Plugin URI: https://github.com/frozzare/wp-glide
 */

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Load WP Glide.
require_once __DIR__ . '/src/class-wp-glide.php';
