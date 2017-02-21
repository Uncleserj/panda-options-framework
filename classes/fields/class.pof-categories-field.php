<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Categories Field
 *
 * @class   Panda_Options_Categories_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Taxonomy_Field' ) ) require_once( 'class.pof-taxonomy-field.php' );

if ( ! class_exists( 'Panda_Options_Categories_Field' ) && class_exists( 'Panda_Options_Taxonomy_Field' ) ) {
	
	class Panda_Options_Categories_Field extends Panda_Options_Taxonomy_Field {
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$this->field->tax = 'category';
		
			parent::output();

		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() {
			
			if ( isset( $this->field->name ) && isset( $this->field->type ) && isset( $this->field->title ) ) return true;
			
			return false;
			
		}
		
		/**
		 * Sanitize input
		 *
		 * @param string|int|array Dirty value
		 */
		
		public function sanitize( $value ) { 
			
			$value = absint( $value );
			
			return $value;
			
		}
		
	}
	
}
?>
