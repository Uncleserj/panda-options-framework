<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * U Theme iFrame Field
 *
 * @class   Panda_Options_Iframe_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Iframe_Field' ) ) {
	
	class Panda_Options_Iframe_Field extends Panda_Options_Field {
		
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
			
			$field = $this->field;	
		
			if ( ! isset( $field->width ) ) $field->width = '100%';
			
			if ( ! isset( $field->height ) ) $field->height = '400px';
			
			if ( ! isset( $field->url ) ) $field->url = get_site_url();
			
			?>
			<iframe src="<?php echo esc_url( $field->url ); ?>" width="<?php echo $this->get_sanitized_text( $field->width ); ?>" height="<?php echo $this->get_sanitized_text( $field->height ); ?>"></iframe>
			<?php	
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() { return true; }
		
	}
	
}