<?php

declare( strict_types = 1 );

namespace SEL\Controller;

use SEL\Model\{Events_Model};

class Events_Controller {

    const POST_TYPE_NAME    = 'Event';
	const POST_TYPE_SLUG    = 'sel-event'; //add prefix in order to avoid conflict with other plugins adding events as a custom post type
	const TAG_NAME          = 'Event';
	const TAG_SLUG          = 'sel-event'; //the same reason applies here as well
    //const PLUGIN_TEXTDOMAIN =  SEL_DIR . '/languages';

    public function __construct() {
        self::register_hooks();

        register_activation_hook( SEL_FILE, array( $this, 'activate' ) );
        register_deactivation_hook( SEL_FILE, array( $this, 'deactivate' ) );
    }

    public static function create_post_type() {
        if ( ! post_type_exists( self::POST_TYPE_SLUG ) ) {
            $post_type_args   = self::get_post_type_args();
            $post_type        = register_post_type( self::POST_TYPE_SLUG, $post_type_args );
            if ( is_wp_error( $post_type ) ) {
                add_notice( __METHOD__ . ' error: ' . $post_type->get_error_message(), 'error' );
            }
        }
    }

    public static function create_taxonomies() {
        if ( ! taxonomy_exists( self::TAG_SLUG ) ) {
            $taxonomy_args = self::get_taxonomy_args();
            register_taxonomy( self::TAG_SLUG, self::POST_TYPE_SLUG, $taxonomy_args );
        }
    }

    public static function save_post( $post_id ) {
        global $post;

        $ignored_actions = array( 'trash', 'untrash', 'restore' );

        if ( isset( $_GET['action'] ) && in_array( $_GET['action'], $ignored_actions ) ) {
            return;
        }

        if ( ! $post || $post->post_type != self::POST_TYPE_SLUG || ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

    }

    public static function register_hooks() {
        add_action('init', __CLASS__ . '::create_post_type' );
        add_action('init', __CLASS__ . '::create_taxonomies' );
        add_action('init', __CLASS__ . '::save_post' );
    }

    public function activate() {
        $this->create_post_type();
        $this->create_taxonomies();

        Events_Model::create_table();

        flush_rewrite_rules();
    }

    public function deactivate() {
        unregister_post_type( self::POST_TYPE_SLUG );

        Events_Model::drop_table();

        flush_rewrite_rules();
    }

    protected static function get_post_type_args() {

        $labels = array(
            'name'               => _x( self::POST_TYPE_NAME . 's', 'post type general name' ),
            'singular_name'      => _x( self::POST_TYPE_NAME, 'post type singular name' ),
            'menu_name'          => _x( self::POST_TYPE_NAME . 's', 'admin menu' ),
            'name_admin_bar'     => _x( self::POST_TYPE_NAME, 'add new on admin bar' ),
            'add_new'            => _x( 'Add New ', 'event' ),
            'add_new_item'       => __( 'Add New ' . self::POST_TYPE_NAME ),
            'new_item'           => __( 'New '     . self::POST_TYPE_NAME ),
            'edit_item'          => __( 'Edit '    . self::POST_TYPE_NAME ),
            'view_item'          => __( 'View '    . self::POST_TYPE_NAME ),
            'all_items'          => __( 'All '     . self::POST_TYPE_NAME . 's' ),
            'search_items'       => __( 'Search '  . self::POST_TYPE_NAME . 's' ),
            'parent_item_colon'  => __( 'Parent '  . self::POST_TYPE_NAME . 's:' ),
            'not_found'          => __( 'No ' . mb_strtolower( self::POST_TYPE_NAME ) . 's found.' ), //mb_stringtolower because of unicode chars
            'not_found_in_trash' => __( 'No ' . mb_strtolower( self::POST_TYPE_NAME ) .'s found in Trash.' ) //mb_stringtolower because of unicode chars
		);

        $post_type_args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => mb_strtolower( self::POST_TYPE_NAME ) ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 21.292892729,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        return $post_type_args;

    }

    protected static function get_taxonomy_args() {

        $tag_taxonomy_params = array(
            'label'                 => self::TAG_NAME,
            'labels'                => array( 'name' => self::TAG_NAME, 'singular_name' => self::TAG_NAME ),
            'hierarchical'          => true,
            'rewrite'               => false,
            'update_count_callback' => '_update_post_term_count'
        );

        return $tag_taxonomy_params;

    }

}
