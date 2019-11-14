<?php

declare( strict_types = 1 );

namespace SEL\Model;

class Events_Model {

    public static function create_table() {
        global $wpdb;

        $sql = '
                CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'sel_events(
                    id int(11) NOT NULL auto_increment,
                    event_title varchar(255) NOT NULL,
                    event_location varchar(255) NOT NULL,
                    event_date DATE NOT NULL,
                    event_link varchar(255) NOT NULL,
                    PRIMARY KEY  (id)
                )COLLATE="utf8_general_ci"';

        dbDelta($sql);
    }

    public static function drop_table() {
        global $wpdb;

        $sql = 'DROP TABLE ' . $wpdb->prefix . 'sel_events';
        $wpdb->query($sql);
    }

    public static function fetch_all_events() {

    }

    public static function fetch_event($event_id) {

    }


}
