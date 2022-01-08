<?php
/**
 * General app functions.
 *
 * @package PHPAPI
 * @version 0.1.0
 */

function parse_args( $args, $defaults = array() ) {
    if ( is_object( $args ) ) {
        $parsed_args = get_object_vars( $args );
    } elseif ( is_array( $args ) ) {
        $parsed_args =& $args;
    } else {
        parse_str( $args, $parsed_args );
    }

    if ( is_array( $defaults ) && $defaults ) {
        return array_merge( $defaults, $parsed_args );
    }

    return $parsed_args;
}
