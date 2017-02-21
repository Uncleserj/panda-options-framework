<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Title Field
 *
 * @class   Panda_Options_Title_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Title_Field' ) ) {
	
	class Panda_Options_Title_Field extends Panda_Options_Field {
		
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
					default: $align = null;break;
					
				}
							
			}
			
			if ( ! isset( $field->sub ) ) $field->sub = '';
			
			if ( $align ) $align_style = ' style="text-align:' . $align . ';"';
			?>
			<h2<?php echo $align_style; ?> data-align="<?php echo $align; ?>"><?php echo $this->get_sanitized_text( $field->title ); ?></h2>
			<?php if ( $field->sub != '' ) { ?>
			<p<?php echo $align_style; ?> class="subtitle"><?php echo $this->get_sanitized_text( $field->sub ); ?></p>
			<?php }			
			
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() { return true; }
				
	}
	
}
?>

