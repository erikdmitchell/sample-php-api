<?php
define( 'API_ROOT_PATH', __DIR__ );

// Connect to MySQL database.
include_once( API_ROOT_PATH . '/class-database.php' );

include_once( API_ROOT_PATH . '/abstract/abstract-class-db.php' );

include_once( API_ROOT_PATH . '/class-db-films.php' );

function parse_args( $args, $defaults = array() ) {
    if ( is_object( $args ) ) {
        $parsed_args = get_object_vars( $args );
    } elseif ( is_array( $args ) ) {
        $parsed_args =& $args;
    } else {
        parse_args( $args, $parsed_args ); /** @phpstan-ignore-line */
    }

    if ( is_array( $defaults ) && $defaults ) {
        return array_merge( $defaults, $parsed_args );
    }

    return $parsed_args;
}
