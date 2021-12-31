<?php
define("API_ROOT_PATH", __DIR__);

// Connect to MySQL database.
include_once( API_ROOT_PATH . '/class-database.php' );

// abstract/abstract-class-db.php

include_once( API_ROOT_PATH . '/db-class.php' ); // db test class.

function parse_args( $args, $defaults = array() ) {
    if ( is_object( $args ) ) {
        $parsed_args = get_object_vars( $args );
    } elseif ( is_array( $args ) ) {
        $parsed_args =& $args;
    } else {
        wp_parse_str( $args, $parsed_args );
    }
 
    if ( is_array( $defaults ) && $defaults ) {
        return array_merge( $defaults, $parsed_args );
    }
    return $parsed_args;
}