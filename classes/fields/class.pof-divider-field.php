<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Divider Field
 *
 * @class   Panda_Options_Divider_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Divider_Field' ) ) {
	
	class Panda_Options_Divider_Field extends Panda_Options_Field {
		
		/**
		 * Is field simple or not
		 *
		 * @access public
		 * @var bool
		 */
		 
		public $simple_type = true;
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			echo '<hr/>';		
			
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() { return true; }
		
	}
	
}
?>
