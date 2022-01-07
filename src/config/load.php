<?php
/**
 * Load all includes
 *
 * @package PHPAPI
 * @version 0.1.0
 */

// Connect to MySQL database.
include_once( API_ROOT_PATH . 'config/database.php' );

//include_once( API_ROOT_PATH . 'database/abstract-db.php' );

//include_once( API_ROOT_PATH . 'database/films.php' );

/**
 * Parse array args.
 *
 * @access public
 * @param mixed $args array.
 * @param array $defaults (default: array()).
 * @return array
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
