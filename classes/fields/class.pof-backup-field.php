<?php
if ( ! defined( 'WPINC' ) || ! defined( 'POF' ) ) die;

/**
 * Panda Options Backup Field
 *
 * @class   Panda_Options_Backup_Field
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

if ( ! class_exists( 'Panda_Options_Backup_Field' ) ) {
	
	class Panda_Options_Backup_Field extends Panda_Options_Field {
		
		/**
		 * Ajax init
		 */
				
		public function ajax_init() {
						
			add_action( 'wp_ajax_ajax_backup_update_settings_field', array( $this, 'ajax_backup_update_settings_field' ) );
									
			add_action( 'wp_ajax_ajax_backup_update_list', array( $this, 'ajax_backup_update_list' ) );
					
			add_action( 'wp_ajax_ajax_backup_restore', array( $this, 'ajax_backup_restore' ) );
			
			add_action( 'wp_ajax_ajax_backup_insert', array( $this, 'ajax_backup_insert' ) );
			
			add_action( 'wp_ajax_ajax_backup_create', array( $this, 'ajax_backup_create' ) );
			
			add_action( 'wp_ajax_ajax_backup_delete', array( $this, 'ajax_backup_delete' ) );
		}
		
		/**
		 * Ajax: Print serialized current options to backup textarea
		 */
		
		public function ajax_backup_update_settings_field() {
			
			check_ajax_referer( POF, 'security' );
			
			$this->prefix = $_REQUEST[ 'ajaxprefix' ];
			
			$options = '';

			if ( $this->get_decoded_options() ) {
			
				$options = $this->get_decoded_options()[0]->option_value;
			
				echo base64_encode( $options );	

			}
					
			wp_die();
		}
		
		/**
		 * Ajax: print all backups
		 */	
		
		public function ajax_backup_update_list() {
			
			check_ajax_referer( POF, 'security' );
			
			$this->prefix = $_REQUEST[ 'ajaxprefix' ];
			
			$data = $this->get_backups_list();
						
			echo $data;
			
			wp_die();
		}
		
		/**
		 * Ajax: create backup
		 */
		
		public function ajax_backup_create() {
						
			check_ajax_referer( POF, 'security' );
						
			$this->prefix = $_REQUEST[ 'ajaxprefix' ];
			
			$value = $_POST['value'];
			
			$this->create_backup_options( $value );

			wp_die();
		}
		
		/**
		 * Ajax: insert options from builder textarea
		 */
		
		public function ajax_backup_insert() {
			
			check_ajax_referer( POF, 'security' );
			
			$this->prefix = $_REQUEST[ 'ajaxprefix' ];
			
			$value = $_POST['value'];

			$this->update_options( $value );
					
			wp_die();
		}
	
		/**
		 * Ajax: delete backup
		 */
		
		public function ajax_backup_delete() {
			
			check_ajax_referer( POF, 'security' );
			
			$this->prefix = $_REQUEST[ 'ajaxprefix' ];
			
			$timestamp = intval( $_POST['time_stamp'] );

			$this->delete_options( $timestamp );
			
			wp_die();
		}
		
		/**
		 * Ajax: restore options from backup
		 */
		
		public function ajax_backup_restore() {
			
			check_ajax_referer( POF, 'security' );
			
			$this->prefix = $_REQUEST[ 'ajaxprefix' ];
			
			global $wpdb;
			
			$timestamp = intval( $_POST['time_stamp'] );

			$value = $this->get_decoded_options( $timestamp );
			
			$value = base64_encode( $value[0]->option_value );
			
			$this->update_options( $value );
					
			wp_die();
		}
		
		/**
		 * Field hooks
		 */

		public function hooks() {
			
			add_action( 'pof_backup_ajax', array( $this, 'ajax_init' ) );
						
			add_action( 'admin_footer', function() {
			
				if ( ! wp_style_is( 'pof-backup-field', 'registered' ) ) {
					
					global $_wp_admin_css_colors;
					
					if ( $_wp_admin_css_colors ) $admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
										
					wp_register_style( 'pof-backup-field', __FILE__ ); ?>
					
					<style id="u-theme-backup-field-css" type="text/css">
						<?php echo '.' . $this->main_css_class . ' .backups-manager .create-options {background-color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .backups-manager .create-options:hover {background-color: ' . $admin_colors[1] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .backups-manager .insert-options {background-color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .backups-manager .insert-options:hover {background-color: ' . $admin_colors[1] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .backups-manager .export-options {background-color: ' . $admin_colors[3] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .backups-manager .export-options:hover {background-color: ' . $admin_colors[1] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' .backups button {background-color:' . $admin_colors[0] . '}'; ?>
						<?php echo '.' . $this->main_css_class . ' textarea:focus {border-color: ' . $admin_colors[0] . ';-webkit-box-shadow: 0 0 2px ' . $admin_colors[0] . ';box-shadow: 0 0 2px ' . $admin_colors[0] . '}'; ?>
					</style>
			
			<?php }
				
			} );
			
		}
		
		/**
		 * Print field content
		 */
				
		public function output() {
						
			$field = $this->field;
			$prefix = $this->prefix;
			
			$options = '';
			
			if ( $this->get_decoded_options() ) {
			
				$options = $this->get_decoded_options()[0]->option_value;
			
			}
	
			?>
			<textarea id='<?php echo $field->name; ?>' placeholder='<?php _e( 'Options not defined...', POF_LANG ); ?>'><?php echo base64_encode( $options ); ?></textarea>
			<?php if ( isset( $field->desc ) ) { ?>
			<label class="<?php echo $field->type; ?>-label" for="<?php echo $field->name; ?>"><?php echo $this->get_sanitized_text( $field->desc ); ?></label>
			<?php } ?>
			
			<div class="backups-manager">
			
				<button type="button" class="create-options" data-options="<?php echo base64_encode( $options ); ?>">
					<i class="icon-plus plus"></i> <?php _e( 'Create backup', POF_LANG ); ?>
				</button>
				
				<button type="button" class="insert-options" style="margin-left: 6px;" data-options="<?php echo base64_encode( $options ); ?>">
					<i class="icon-upload-cloud up"></i> <?php _e( 'Insert options', POF_LANG ); ?>
				</button>
	
				<ul class="backups">
							
				<?php echo $this->get_backups_list(); ?>
				
				</ul>
			
			</div>
			
			<?php	
		}
			
		/**
		 * Get serialized options from backup by timestamp
		 *
		 * @global object $wpdb Wordpress Database
		 * @param string $timestamp Timestamp of backup
		 * @return object Options
		 */
		
		public function get_decoded_options( $timestamp = null, $prefix = null ) {
			
			global $wpdb;
			
			if ( $timestamp ) $timestamp = '_backup_' . $timestamp;
			
			if ( ! $prefix ) $prefix = $this->prefix;
			
			return $wpdb->get_results(
				"SELECT option_value FROM {$wpdb->options} WHERE option_name = '" . POF . $prefix . $timestamp . "'"
			);
		}
			
		/**
		 * Get all backups from database
		 *
		 * @global object $wpdb Wordpress Database
		 * @return array Backups
		 */
		
		public function get_backups( $prefix = null ) {
			
			global $wpdb;
			
			if ( ! $prefix ) $prefix = $this->prefix;
			
			return $wpdb->get_results(
				"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '" . POF . $prefix . '_backup_%' . "'"
			);
		}
		
		/**
		 * Insert options from value in database
		 *
		 * @global object $wpdb Wordpress Database
		 * @param string $value Serialized options
		 */
		
		public function create_current_options( $value, $prefix = null ) {
		
			global $wpdb;
			
			if ( ! $prefix ) $prefix = $this->prefix;
								
			$new_options = base64_decode( $value );
			
			$wpdb->insert( 
				$wpdb->options, 
				array( 
					'option_name' => POF . $prefix, 
					'option_value' => $new_options 
				), 
				array( 
					'%s', 
					'%s' 
				) 
			);
	
		}
		
		/**
		 * Create backup from value
		 *
		 * @global object $wpdb Wordpress Database
		 * @param string $value Serialized options
		 */
		
		public function create_backup_options( $value, $prefix = null ) {
					
			global $wpdb;
			
			if ( ! $prefix ) $prefix = $this->prefix;
								
			$new_options = base64_decode( $value );
			
			$wpdb->insert( 
				$wpdb->options, 
				array( 
					'option_name' => POF . $prefix . '_backup_' . current_time( 'timestamp' ), 
					'option_value' => $new_options 
				), 
				array( 
					'%s', 
					'%s' 
				) 
			);
	
		}
		
		/**
		 * Delete options from database
		 *
		 * @global object $wpdb Wordpress Database
		 * @param string $timestamp Timestamp od backup
		 */
		
		public function delete_options( $timestamp = null, $prefix = null ) {
			
			global $wpdb;
			
			if ( ! $prefix ) $prefix = $this->prefix;
			
			if ( $timestamp ) $timestamp = '_backup_' . $timestamp;
			
			$wpdb->delete( 
				$wpdb->options, 
				array( 
					'option_name' => POF . $prefix . $timestamp 
				), 
				array( 
					'%s' 
				) 
			);
	
		}
		
		/**
		 * Update options in database
		 *
		 * @global object $wpdb Wordpress Database
		 * @param string $value Serialized options
		 */
		
		public function update_options( $value, $prefix = null ) {
			
			global $wpdb;
			
			if ( ! $prefix ) $prefix = $this->prefix;
			
			$value = base64_decode( $value );
			
			$wpdb->replace( 
				$wpdb->options, 
				array( 
					'option_name' => POF . $prefix,
					'option_value' => $value
				), 
				array( 
					'%s',
					'%s'
				)
			);
			
		}
			
		/**
		 * Create backups list
		 *
		 * @return string HTML List of backups
		 */
		
		public function get_backups_list( $prefix = null ) {
			
			if ( ! $prefix ) $prefix = $this->prefix;
			
			$data = '';
					
			foreach ( $this->get_backups( $prefix ) as $backup ) {
				
				$timestamp = intval( str_replace( POF . $prefix . '_backup_', '', $backup->option_name ) );
				$date_of_backup = strftime( __( '%m.%d.%Y - %I:%M %p', POF_LANG ), $timestamp );
				
				$data .= '<li>';
				$data .= '<span class="backup-item"><i class="icon-database icon"></i><span>' . $date_of_backup . '</span>';
				$data .= '<button type="button" class="delete-options" id="' . $timestamp . '" href="#" title="' . __( 'Delete', POF_LANG ) . '"><i class="icon-block"></i></button>';
				$data .= '<button type="button" class="restore-options" id="' . $timestamp . '" href="#" title="' . __( 'Restore', POF_LANG ) . '"><i class="icon-arrows-ccw"></i></button>';
				$data .= '</span>';
				$data .= '</li>';
				
			}
				
			return $data;
		}
		
		/**
		 * Check required attributes of field
		 */
		
		public function check_field() { return true; }
				
	}
	
}