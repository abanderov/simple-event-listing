<?php

declare( strict_types = 1 );

namespace SEL\Controller;

if ( ! class_exists( 'Events_Controller' ) ) {

    class Events_Controller {

        const POST_TYPE_NAME    = 'Event';
    	const POST_TYPE_SLUG    = 'sel-event'; //add prefix in order to avoid conflict with other plugins adding events as a custom post type
    	const TAG_NAME          = 'Event';
    	const TAG_SLUG          = 'sel-event'; //the same reason applies here as well
        //const PLUGIN_TEXTDOMAIN =  SEL_DIR . '/languages';

        private static $instance;

        public static function get_instance() {
            if ( ! isset( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        protected function __construct() {
            self::register_hooks();

            register_activation_hook( SEL_FILE, array( $this, 'activate' ) );
            register_deactivation_hook( SEL_FILE, array( $this, 'deactivate' ) );
        }

        public static function register_hooks() {
            add_action( 'init', __CLASS__ . '::create_post_type' );
            add_action( 'init', __CLASS__ . '::create_taxonomies' );
            add_action( 'admin_enqueue_scripts', __CLASS__ . '::add_styles' );
            add_action( 'admin_enqueue_scripts', __CLASS__ . '::add_scripts' );
            add_action( 'add_meta_boxes', __CLASS__ . '::add_metaboxes' );
            add_action( 'save_post', __CLASS__ . '::save_location_meta_box_data' );
            add_action( 'save_post', __CLASS__ . '::save_date_meta_box_data' );
        }

        public function activate() {
            $this->create_post_type();
            $this->create_taxonomies();

            flush_rewrite_rules();
        }

        public function deactivate() {
            unregister_post_type( self::POST_TYPE_SLUG );

            flush_rewrite_rules();
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

        public static function save_location_meta_box_data( $post_id ) {

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

                if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return;
                }

            }
            else {

                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            }

            $location_data = array(
                'sel_location' => ! empty( $_POST['sel_location']) ? sanitize_text_field( $_POST['sel_location'] ) : '',
                'sel_latitude' => ! empty( $_POST['sel_latitude']) ? sanitize_text_field( $_POST['sel_latitude'] ) : '',
                'sel_longitude' => ! empty( $_POST['sel_latitude']) ? sanitize_text_field( $_POST['sel_longitude'] ) : '',
            );

            update_post_meta( $post_id, '_sel_location', $location_data );
        }

        public static function save_date_meta_box_data( $post_id ) {

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

                if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return;
                }

            }
            else {

                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            }

            $date_data = array(
                'sel_to' => ! empty( $_POST['sel_to']) ? sanitize_text_field( $_POST['sel_to'] ) : '',
                'sel_from' => ! empty( $_POST['sel_from']) ? sanitize_text_field( $_POST['sel_from'] ) : '',
                'sel_time' => ! empty( $_POST['sel_time']) ? sanitize_text_field( $_POST['sel_time'] ) : '',
            );

            update_post_meta( $post_id, '_sel_date', $date_data );
        }

        public static function add_metaboxes( $post_type ) {
            $post_types = array( 'sel-event' );

            if ( in_array( $post_type, $post_types ) ) {
                //Adds Google Maps Meta Box
                add_meta_box(
                    'sel_location',
                    __( 'Location', 'textdomain' ),
                    array( __CLASS__, 'render_meta_box_location' ),
                    $post_type,
                    'side',
                    'default'
                );

                //Adds DatePicker Meta Box
                add_meta_box(
                    'sel_date',
                    __( 'Date', 'textdomain' ),
                    array( __CLASS__, 'render_meta_box_date' ),
                    $post_type,
                    'side',
                    'default'
                );
            }

        }

        public static function render_meta_box_location() {
            $location_value = get_post_meta( get_the_ID(), '_sel_location', true );

            require_once( SEL_ROOT . '/view/metaboxes/event-location.php');
        }

        public static function render_meta_box_date() {
            $date_value = get_post_meta( get_the_ID(), '_sel_date', true );

            require_once( SEL_ROOT . '/view/metaboxes/event-date.php');
        }

        public static function add_scripts() {
            wp_enqueue_script( 'jquery-ui', plugin_dir_url( SEL_FILE ) .  '/assets/js/jquery-ui.js');
        }

        public static function add_styles() {
            wp_enqueue_style( 'jquery-ui', plugin_dir_url( SEL_FILE ) . '/assets/css/jquery-ui.css' );
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
                'label'                 => self::TAG_NAME . 's\' tags',
                'labels'                => array( 'name' => self::TAG_NAME . 's\' tags', 'singular_name' => self::TAG_NAME . 'tag'),
                'hierarchical'          => true,
                'rewrite'               => false,
                'update_count_callback' => '_update_post_term_count'
            );

            return $tag_taxonomy_params;

        }

    }

    Events_Controller::get_instance();
}
