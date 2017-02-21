<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Posts Field
 *
 * @class   Panda_Options_Posts_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Posts_Field' ) ) {
	
	class Panda_Options_Posts_Field extends Panda_Options_Field {
		
		/**
		 * Print field content
		 */
				
		public function output() {
			
			$field = $this->field;
			$options = $this->options;
			$prefix = $this->prefix;
		
			isset( $field->std ) ? $default = $this->sanitize( $field->std ) : $default = '';
			
			if ( ! isset( $options[$field->name] ) ) $options[$field->name] = $default;
			
			if ( ! isset( $field->posttype ) || ! post_type_exists( $field->posttype ) ) $field->posttype = 'post';
								
			?>
			<select class='select' data-posttype='<?php echo $field->posttype; ?>'id='<?php echo $field->name; ?>' name='<?php echo POF . $prefix; ?>[<?php echo $field->name; ?>]'>
			<?php
						
			$posts = get_posts( array( 'post_type' => $field->posttype ) );
																				
			foreach ( $posts as $post ) { ?>
			
				<option id='<?php echo $post->ID ; ?>' <?php selected( esc_attr( $options[$field->name] ), $post->ID , true ); ?> value='<?php echo esc_attr( $post->ID  ); ?>'><?php echo esc_attr( $post->post_title ); ?></option>	
			
			<?php } ?>
			
			</select>
			<?php if ( isset( $field->desc ) ) { ?>
			<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php echo $this->get_sanitized_text( $field->desc ); ?></label>
			<?php }
		}
		
		/**
		 * Field hooks
		 */
		
		public function hooks() {
						
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-select-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
					
					wp_register_style( 'pof-select-field', __FILE__ ); ?>
			
					<style id="pof-select-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' select {background-color: ' . $admin_colors[3] . '}'; ?>
					</style>
			
			<?php }
				
			} );
			
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
