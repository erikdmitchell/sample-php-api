<?php
/**
 * Install class
 *
 * @package PHPAPI
 * @version 0.1.0
 */

/**
 * Install class.
 */
class Install {
    /**
     * Init function.
     *
     * @access public
     * @static
     * @return void
     */
    public static function init() {
        self::run_install();
    }

    /**
     * Install function.
     *
     * @access public
     * @static
     * @return void
     */
    public static function run_install() {
        self::update_db();
    }

    /**
     * Update DB.
     *
     * @access private
     * @static
     * @return void
     */
    private static function update_db() {
    }

}

// run init.
Install::init();
