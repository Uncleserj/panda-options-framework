<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Image Field
 *
 * @class   Panda_Options_Image_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Image_Field' ) ) {
	
	class Panda_Options_Image_Field extends Panda_Options_Field {
		
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
			
			$align = 'center';
			
			if ( isset( $field->align ) ) {
								
				switch ( $field->align ) {
					
					case 'left': $align = 'left';break;
					case 'right': $align = 'right';break;
					case 'center': $align = 'center';break;
					default: $align = 'center';break;
					
				}
										
			}
			
			isset( $field->width ) ? $width = ' width="' . intval( $field->width ) . '"' : $width = '';
			isset( $field->height ) ? $height = ' height="' . intval( $field->height ) . '"' : $height = '';
			
			?>
			<div class="u_ops_align_<?php echo $align; ?>">
				<img class="u_ops_image" data-align="<?php echo $align; ?>" src="<?php echo esc_url( $field->url ); ?>"<?php echo $width.$height; ?> />
			</div>
			<?php	
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() { return true; }
		
	}
	
}
?>
