<?php

declare( strict_types = 1 );

namespace SEL\Controller;

if ( ! class_exists( 'Events_Controller' ) ) {

    class Events_Controller {

        const POST_TYPE_NAME    = 'Event';
        //add prefix in order to avoid conflict with other plugins adding events as a custom post type
      	const POST_TYPE_SLUG    = 'sel-event';
      	const TAG_NAME          = 'Event';
      	const TAG_SLUG          = 'sel-event';

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
            add_action( 'save_post', __CLASS__ . '::save_ext_url_meta_box_data' );
            add_filter( 'single_template', __CLASS__ . '::load_custom_template_single_post' );
            add_filter( 'archive_template', __CLASS__ . '::load_custom_template_single_archive' );
        }

        public function activate() {
            self::create_post_type();
            self::create_taxonomies();

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

            } else {

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

            } else {
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            }

            $date_data = array(
                'sel_to' => ! empty( $_POST['sel_to'] ) ? sanitize_text_field( $_POST['sel_to'] ) : '',
                'sel_from' => ! empty( $_POST['sel_from'] ) ? sanitize_text_field( $_POST['sel_from'] ) : '',
                'sel_start_time' => ! empty( $_POST['sel_start_time'] ) ? sanitize_text_field( $_POST['sel_start_time'] ) : '',
                'sel_end_time' => ! empty( $_POST['sel_end_time'] ) ? sanitize_text_field( $_POST['sel_end_time'] ) : '',
            );

            update_post_meta( $post_id, '_sel_date', $date_data );
            update_post_meta( $post_id, '_sel_start_date', strtotime( $_POST['sel_from'] ) );
        }

        public static function save_ext_url_meta_box_data( $post_id ) {

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

                if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return;
                }

            } else {

                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }
            }

            $ext_url_data = ! empty( $_POST['sel_ext_url'] ) ? sanitize_text_field( $_POST['sel_ext_url'] ) : '';

            update_post_meta( $post_id, '_sel_ext_url', $ext_url_data );
        }

        public static function add_metaboxes( $post_type ) {
            $post_types = array( 'sel-event' );

            if ( in_array( $post_type, $post_types ) ) {
                //Add Google Maps Meta Box
                add_meta_box(
                    'sel_location',
                    __( 'Location', 'textdomain' ),
                    array( __CLASS__, 'render_meta_box_location' ),
                    $post_type,
                    'side',
                    'default'
                );

                //Add DatePicker Meta Box
                add_meta_box(
                    'sel_date',
                    __( 'Date', 'textdomain' ),
                    array( __CLASS__, 'render_meta_box_date' ),
                    $post_type,
                    'side',
                    'default'
                );

                //Add External URL Meta Box
                add_meta_box(
                    'sel_ext_url',
                    __( 'External URL', 'textdomain' ),
                    array( __CLASS__, 'render_meta_box_ext_url' ),
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

        public static function render_meta_box_ext_url() {
            $ext_url_data = get_post_meta( get_the_ID(), '_sel_ext_url', true );

            require_once( SEL_ROOT . '/view/metaboxes/event-ext-url.php');
        }

        public static function add_scripts() {
            wp_enqueue_script( 'jquery-ui', plugin_dir_url( SEL_FILE ) .  '/assets/js/jquery-ui.js');
        }

        public static function add_styles() {
            wp_enqueue_style( 'jquery-ui', plugin_dir_url( SEL_FILE ) . '/assets/css/jquery-ui.css' );
        }

        public static function load_custom_template_single_post( $single ) {
            global $post;

            if ( $post->post_type == self::POST_TYPE_SLUG ) {
               if ( file_exists( SEL_ROOT . '/view/templates/single-sel-event.php' ) ) {
                    return ( SEL_ROOT . '/view/templates/single-sel-event.php' );
               }
            }

            return $single;
        }

        public static function load_custom_template_single_archive( $archive_template ) {
             global $post;

             if ( is_post_type_archive ( self::POST_TYPE_SLUG ) ) {
                 if ( file_exists( SEL_ROOT . '/view/templates/archive-sel-event.php' ) ) {
                     return SEL_ROOT . '/view/templates/archive-sel-event.php';
                 }
             }
             return $archive_template;
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
                'archives'           => __( self::POST_TYPE_NAME . 's' . 'Archives' ),
                'search_items'       => __( 'Search '  . self::POST_TYPE_NAME . 's' ),
                'parent_item_colon'  => __( 'Parent '  . self::POST_TYPE_NAME . 's:' ),
                //mb_stringtolower because of unicode chars
                'not_found'          => __( 'No ' . mb_strtolower( self::POST_TYPE_NAME ) . 's found.' ),
                'not_found_in_trash' => __( 'No ' . mb_strtolower( self::POST_TYPE_NAME ) .'s found in Trash.' )
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
                'label'                 => 'Tags',
                'labels'                => array( 'name' => 'Tags', 'singular_name' => 'Tag'),
                'hierarchical'          => true,
                'rewrite'               => false,
                'update_count_callback' => '_update_post_term_count'
            );

            return $tag_taxonomy_params;
        }

    }

    Events_Controller::get_instance();
}
