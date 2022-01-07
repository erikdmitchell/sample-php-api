<?php
/**
 * Films database class
 *
 * @package PHPAPI
 * @version 0.1.0
 */
 
define( 'API_ROOT_PATH', __DIR__ );

// Connect to MySQL database.
include_once( API_ROOT_PATH . '/class-database.php' );

include_once( API_ROOT_PATH . '/abstract/abstract-class-db.php' );

include_once( API_ROOT_PATH . '/class-db-films.php' );

/**
 * Parse array args.
 * 
 * @access public
 * @param mixed $args array
 * @param array $defaults (default: array()).
 * @return void
 */
function parse_args( $args, $defaults = array() ) {
    $parsed_args = '';

    if ( is_object( $args ) ) {
        $parsed_args = get_object_vars( $args );
    } elseif ( is_array( $args ) ) {
        $parsed_args =& $args;
    } else {
        parse_args( $args, $parsed_args );
    }

    if ( is_array( $defaults ) && $defaults ) {
        return array_merge( $defaults, $parsed_args );
    }

    return $parsed_args;
}
