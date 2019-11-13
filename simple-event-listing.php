<?php
/*
  Plugin Name:      Simple Event Listing
  Author:           Aleksandar Banderov
  Description:      A simple event listing plugin for WordPress
  Version:          1.0
  Domain Path:      /languages
*/


if ( ! defined (ABSPATH) ) {
    die( 'Access denied!' );
}

define( 'PLUGIN_NAME' , 'Simple Event Listing' );
define( 'PHP_REQ_VERSION', '7.0' ); //because of declare(strict_types = 1);
define( 'WORDPRESS_REQ_VERSION', '4.0' );


function requirements_are_met() {
    global $wp_version;

    if ( version_compare($wp_version, PHP_REQ_VERSION, '<' ) {
        return false;
    }

    if ( version_compare($wp_version, WORDPRESS_REQ_VERSION, '<' ) {
        return false;
    }

    return true;
}

function requirements_not_met() {
    global $wp_version;

    require_once( __DIR__ . '/view/requirements-error.php');
}

if ( requirements_are_met() ) {
    require_once( __DIR__ . '/system/interfaces/custom-post-type.php');
    require_once( __DIR__ . '/model/class-events-model.php');
    require_once( __DIR__ . '/controller/class-events-controller.php');

    if ( class_exists( 'EventsController' ) ) {
        $sel = new Events_Controller();

        register_activation_hook( __FILE__, array( $sel, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $sel, 'deactivate' ) );
    }
} else {
    add_action( 'admin_notices', 'requirements_not_met' );
}
