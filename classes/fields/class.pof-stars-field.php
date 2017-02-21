<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Stars Field
 *
 * @class   Panda_Options_Stars_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Stars_Field' ) ) {
	
	class Panda_Options_Stars_Field extends Panda_Options_Field {
				
		/**
		 * Print field content
		 */
		 
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
			
			isset( $field->std ) ? $default = intval( $field->std ) : $default = '';
				
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
		
			?>
			<fieldset class="stars">
			<?php
			
			if ( isset( $field->stars ) ) {
				
				$field->stars = intval($field->stars);
																								
				while ( $field->stars > 0 ) {
	
				?>
				<input type='radio' id='<?php echo $field->name . '_' . $field->stars; ?>' class='radiostars' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' <?php checked( esc_attr( $options[$field->name] ), $field->stars, true ); ?> value='<?php echo $field->stars; ?>'>
					
				<label for="<?php echo $field->name . '_' . $field->stars; ?>"><span></span></label>
				<?php 
				$field->stars--;
				} 
			}
			?>
				<div class="clear"></div>
			</fieldset>
			<?php if ( isset( $field->desc ) ) { ?>
			<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php echo $this->get_sanitized_text( $field->desc ); ?></label>
			<?php }
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() {
			
			if ( isset( $this->field->name ) && isset( $this->field->type ) && isset( $this->field->title ) ) return true;
			
			return false;
			
		}
		
		/**
		 * Field hooks
		 */
		
		public function hooks() {
						
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-stars-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-stars-field', __FILE__ ); ?>
			
					<style id="pof-stars-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' fieldset.stars:not(:checked) > label {color: ' . $admin_colors[1] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' fieldset.stars > input:checked ~ label {color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' fieldset.stars > .radiostars:checked ~ label:hover {color: ' . $admin_colors[3] . '}'; ?>
					</style>
			
			<?php }
				
			} );
			
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
