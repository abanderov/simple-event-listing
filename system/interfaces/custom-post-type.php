<?php

declare( strict_types = 1 );

interface Custom_Post_Type {

    public static function create_post_type();

    public static function create_taxonomies();

    public static function save_post( $post_id, $revision );
}
