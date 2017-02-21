<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Html Field
 *
 * @class   Panda_Options_Html_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Html_Field' ) ) {
	
	class Panda_Options_Html_Field extends Panda_Options_Field {
		
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
			
			$align = 'left';
		
			if ( isset( $field->align ) ) {
								
				switch ( $field->align ) {
					
					case 'left': $align = 'left';break;
					case 'right': $align = 'right';break;
					case 'center': $align = 'center';break;
					default: $align = null;break;
					
				}
							
			}
			
			if ( $align ) $align_style = ' style="text-align:' . $align . ';"';
			
			?>
			<div<?php echo $align_style; ?>><?php echo $this->get_sanitized_text( $field->html ); ?></div>
			<?php	
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() { return true; }
		
	}
	
}