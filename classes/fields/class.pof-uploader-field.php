<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Uploader Field
 *
 * @class   Panda_Options_Uploader_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Uploader_Field' ) ) {
	
	class Panda_Options_Uploader_Field extends Panda_Options_Field {
		
		/**
		 * Field hooks
		 */
		
		public function hooks() {
						
			if ( function_exists( 'wp_enqueue_media' ) ) {
			   
			    wp_enqueue_media();
			
			} else {
				
			    wp_enqueue_style( 'thickbox' );
			    wp_enqueue_script( 'media-upload' );
			    wp_enqueue_script( 'thickbox' );
			}
			
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-uploader-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-uploader-field', __FILE__ ); ?>
			
					<style id="pof-uploader-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .u-theme-uploader-button {background-color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .u-theme-uploader-button-delete {background-color: ' . $admin_colors[3] . '}'; ?>
					</style>
			
			<?php }
				
			} );
			
		}
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
			
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = '';
			
			?>
			
			<div class="pof-uploader-block">
				
				<input class='pof-uploader' type='hidden' id='<?php echo $field->name; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]' value='<?php echo esc_attr( $options[$field->name] ); ?>' />
				
				<?php
				if ( isset( $options[$field->name] ) && esc_url( $options[$field->name] ) ) {
				?>
				<img class='pof-preview' src='<?php echo esc_url( $options[$field->name] ); ?>' />
				<?php 
				} 
				?>
			
				<button type="button" class='pof-button pof-uploader-button'>
					<i class="icon-upload pof-uploader-icon"></i> <?php _e( 'Upload', POF_LANG ); ?>
				</button>
				
				<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php echo $this->get_sanitized_text( $field->desc ); ?></label>
				<?php
					
				if ( isset( $options[$field->name] ) && esc_attr( $options[$field->name] ) ) {
					?>
					<p class='pof-upload-link'><i class='icon-link'></i> <?php echo esc_url( $options[$field->name] ); ?></p>
					<button type="button" class='u-theme-button pof-uploader-button-delete'>
						<i class='icon-block pof-uploader-icon'></i> <?php _e( 'Delete', POF_LANG ); ?>
					</button>
					<?php 
				}
				
				?>
			
			</div>
			
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
		 * Sanitize input
		 *
		 * @param string|int|array Dirty value
		 */	
		
		public function sanitize( $value ) { 
			
			$value = esc_url( $value );
			
			return $value;
			
		}
		
	}
	
}