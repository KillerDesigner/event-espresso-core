<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			Seth Shoultes
 * @ copyright		(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version		 	4.0
 *
 * ------------------------------------------------------------------------
 *
 * EEH_Class_Tools Helper
 *
 * @package		Event Espresso
 * @subpackage	/helpers/
 * @author		Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EEH_Class_Tools {
	
	static $i = 0;
	static $file_line = null;
	
	/**
	 * 	get_called_class - for PHP versions < 5.3
	 *
	 *  @access 	public
	 *  @author	origins:  http://stackoverflow.com/a/1542045
	 *  return string
	 */
	public static function get_called_class() {
		$backtrace = debug_backtrace();
		if ( isset( $backtrace[2] ) && is_array( $backtrace[2] ) && isset( $backtrace[2]['file'] ) && isset( $backtrace[2]['line'] )) {
			if ( self::$file_line == $backtrace[2]['file'] . $backtrace[2]['line'] ) {
				self::$i++;
			} else {
				self::$i = 0;
				self::$file_line = $backtrace[2]['file'] . $backtrace[2]['line'];
			}
			$lines = file( $backtrace[2]['file'] );
			preg_match_all( '/([a-zA-Z0-9\_]+)::' . $backtrace[2]['function'].'/', $lines[$backtrace[2]['line']-1], $matches );
			return $matches[1][ self::$i ];
		}
	}
	
}
// if PHP version < 5.3
if ( ! function_exists( 'get_called_class' )) {
	function get_called_class() {
	    return EEH_Class_Tools::get_called_class();
	}
}
// End of file EEH_Class_Tools.helper.php
// Location: /helpers/EEH_Class_Tools.helper.php