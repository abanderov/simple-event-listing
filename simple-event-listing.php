<?php
/*
  Plugin Name:      Simple Event Listing
  Author:           Aleksandar Banderov
  Description:      A simple event listing plugin for WordPress
  Version:          1.0
  Domain Path:      /languages
*/

declare( strict_types = 1 );

use SEL\Controller\{Events_Controller};

if ( ! defined ( 'ABSPATH' ) ) {
    die( 'Access denied!' );
}

define( 'PLUGIN_NAME' , 'Simple Event Listing' );
define( 'PHP_REQ_VERSION', '7.0' ); //because of declare(strict_types = 1);
define( 'WORDPRESS_REQ_VERSION', '4.7' );
define( 'SEL_FILE', __FILE__ );
define( 'SEL_DIR', dirname( SEL_FILE ) );
define( 'SEL_ROOT', SEL_DIR . '/src' );


function requirements_are_met() {
    global $wp_version;

    if ( ! version_compare( PHP_VERSION, PHP_REQ_VERSION, '>=' ) ) {
        return false;
    }

    if ( ! version_compare( $wp_version, WORDPRESS_REQ_VERSION, '>=' ) ) {
        return false;
    }

    return true;
}

function requirements_not_met() {
    global $wp_version;

    require_once( SEL_ROOT . '/view/requirements-error.php');
}

if ( requirements_are_met() ) {
    require_once( SEL_DIR . '/vendor/autoload.php');
    // require_once( ABSPATH . 'wp-admin/includes/upgrade.php'); // because dbDelta() is used in the model;
    // require_once( SEL_ROOT . '/model/class-events-model.php');
    require_once( SEL_ROOT . '/controller/class-events-controller.php');

    $sel = new Events_Controller();


} else {
    add_action( 'admin_notices', 'requirements_not_met' );
}
