<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Switch Field
 *
 * @class   Panda_Options_Switch_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Switch_Field' ) ) {
	
	class Panda_Options_Switch_Field extends Panda_Options_Field {
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
			
			isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = '0';
						
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
			?>
			<input type='hidden' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' value='0'>
			<input type='checkbox' id='<?php echo $field->name; ?>' class='switch' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' <?php checked( intval( $options[$field->name] ), 1, true ); ?> value='1'>
			<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php if ( isset( $field->desc ) ) {
					echo $this->get_sanitized_text( $field->desc ); } else { echo '&nbsp;'; } ?></label>
			<?php
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
			
				if ( ! wp_style_is( 'pof-switch-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-switch-field', __FILE__ ); ?>
			
					<style id="pof-switch-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .switch:checked + label:before {background-color: ' . $admin_colors[3] . '}'; ?>
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
