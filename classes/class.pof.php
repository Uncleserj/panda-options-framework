<?php
if ( ! defined( 'WPINC' ) ) die;

// Load Basic Field Class

require_once( 'fields/class.pof-basic-field.php' );

/**
 * Panda Options Class
 *
 * @class   Panda_Options
 * @package Panda_Options
 * @version 1.0
 *
 * @author  Uncleserj <serj[at]serj[dot]pro>
 */

class Panda_Options {
	
	/**
	 * Version number
	 *
	 * @access public
	 * @var float
	 */
	
	public $version = '1.0';
	
	/**
	 * Array of section
	 *
	 * @access private
	 * @var array
	 */
			
	private $sections;
	
	/**
	 * Prefix of current options set
	 *
	 * @access private
	 * @var string
	 */
	
	private $prefix;
	
	/**
	 * Options splash screen
	 *
	 * @access private
	 * @var bool
	 */
	
	private $starter = false;
	
	/**
	 * Hide first section if only one appears
	 *
	 * @access private
	 * @var bool
	 */
	
	private $first_section_hidden = true;
	
	/**
	 * Hide status bar
	 *
	 * @access private
	 * @var bool
	 */
	
	private $status_bar_hidden = false;
	
	/**
	 * Top level options menu item
	 *
	 * @access private
	 * @var bool
	 */
	
	private $menu_top = false;
	
	/**
	 * Custom menu item vars
	 *
	 * @access private
	 * @var array
	 */
	
	private $menu_vars;
	
	/**
	 * Custom logo
	 *
	 * @access private
	 * @var array
	 */
	
	private $logo_vars;
	
	/**
	 * Fields set
	 *
	 * @access private
	 * @var array
	 */
	
	private $fields;
	
	/**
	 * Options array
	 *
	 * @access private
	 * @var array
	 */
	
	private $options;
	
	/**
	 * Page slug
	 *
	 * @access private
	 * @var string
	 */
			
	private $page;
	
	/**
	 * Types with ajax
	 *
	 * @access private
	 * @var array
	 */
	
	private $ajax = array( 'backup', 'googlefont' );
				
	/**
	 * Construct
	 */

	public function __construct( $fields = null, $prefix = null ) {
								
		$prefix ? $this->prefix = '_' . $prefix : $this->prefix = '_theme';
		
		$this->page = POF . $this->prefix;
		
		$this->options = $this->get_true_options();
		
		$this->fields = $this->get_current_fields( $fields );
				
		$this->hooks();

	}
	
	/* Public Customize Methods */
	
	/**
	 * Customize menu
	 *
	 * @param array $menu_name Menu name 
	 * @param array $menu_title Menu title	 
	 * @param array $menu_icon Menu icon	 
	 * @param array $top_level Menu level 
	 */
	
	public function menu( $menu_name = null, $menu_title = null, $menu_icon = null, $top_level = null ) {
					
		$this->menu_vars = array();
			
		if ( $menu_name ) {
			
			$this->menu_vars['name'] = $menu_name;
			
		}
		
		if ( $menu_title) {
			
			$this->menu_vars['title'] = $menu_title;
			
		}
		
		if ( $menu_icon ) {
			
			$this->menu_vars['icon'] = $menu_icon;
			
		}
		
		if ( ( $top_level ) 
		  && is_bool( $top_level ) )
		  $this->menu_top = $top_level;
		
	}
	
	/**
	 * Customize width
	 *
	 * @param string $max_width Max options content width	 
	 */
	
	public function content_max_width( $max_width = null ) {
		
		if ( $max_width ) {
			
			$content_max_width = $max_width;
		
			$custom_css = '.pof' . $this->prefix . ' .u_ops_section, .pof' . $this->prefix . ' .logo-holder {max-width: ' . $content_max_width . ';}';
			
			wp_add_inline_style( 'pof-admin-css', $custom_css );
			
		}
		
	}
	
	/**
	 * Customize width
	 *
	 * @param string $max_width Max fields width	 
	 */
	
	public function fields_max_width( $max_width = null ) {
		
		if ( $max_width ) {
			
			$fields_max_width = $max_width;
			
			$custom_css = '.pof' . $this->prefix . ' .grid-item.not-simple-type {max-width: ' . $fields_max_width . ';}';
			
			wp_add_inline_style( 'pof-admin-css', $custom_css );

		}
		
	}
	
	/**
	 * Customize width
	 *
	 * @param string $width Max titles width	 
	 */
	
	public function title_width( $width = null ) {
		
		if ( $width ) {
			
			$title_width = $width;
			
			$custom_css = '.pof' . $this->prefix . ' .form-table th {width: ' . $title_width . ';}';
			
			wp_add_inline_style( 'pof-admin-css', $custom_css );
				
		}
		
	}
	
	/**
	 * Customize content align
	 *
	 * @param string $align Max options width	 
	 */
	
	public function content_align( $align = null ) {
		
		$content_align = null;
		
		if ( $align ) {
			
			switch ( $align ) {
				
				case 'left': $content_align = 'left';break;
				case 'right': $content_align = 'right';break;
				case 'center': $content_align = 'center';break;
				default: $content_align = null;break;
				
			}
			
			if ( $content_align != null ) {
			
				$custom_css = '.pof' . $this->prefix . ' .u_ops_section, .u_theme' . $this->prefix . ' .logo-holder {margin-' . $content_align . ':0}';
				
				$custom_css .= '.pof' . $this->prefix . ' .grid-item.not-simple-type {margin-' . $content_align . ':0}';
	
				$custom_css .= '.pof' . $this->prefix . ' .tabs-links {text-align:' . $content_align . '}';
				
				$custom_css .= '.pof' . $this->prefix . ' .submit {text-align:' . $content_align . '}';
				
				wp_add_inline_style( 'pof-admin-css', $custom_css );
			
			}
			
		}
		
	}
	
	/**
	 * Customize first section visibality
	 *
	 * @param bool $hidden Is hidden	 
	 */
	
	public function first_section_hidden( $hidden = true ) {
		
		if ( is_bool( $hidden ) ) $this->first_section_hidden = $hidden;
		
	}
		
	/**
	 * Customize status bar visibality
	 *
	 * @param bool $hidden Is hidden	 
	 */
	
	public function status_bar_hidden( $hidden = false ) {
		
		if ( is_bool( $hidden ) ) $this->status_bar_hidden = $hidden;
		
	}
	
	/**
	 * Customize logo
	 *
	 * @param array $url Logo url 
	 * @param array $align Alignment	 
	 * @param array $width Width	 
	 * @param array $height Height
	 */
	
	public function set_logo( $url, $align = 'center', $width = null, $height = null ) {
							
		switch ( $align ) {
			
			case 'left': $align = 'left';break;
			case 'right': $align = 'right';break;
			case 'center': $align = 'center';break;
			default: $align = 'center';break;
			
		}
		
		$url = esc_url( $url );
		$width = intval( $width );
		$height = intval( $height );
		
		if ( $url ) $this->logo_vars['url'] = $url;
		if ( $align ) $this->logo_vars['align'] = $align;
		if ( $width ) $this->logo_vars['width'] = $width;
		if ( $height ) $this->logo_vars['height'] = $height;
		
	}
	
	/* Public Main Methods */
	
		
	/**
	 * Register Wordpress hooks
	 */
	
	public function hooks() {
		
        // jQuery Form
        
        wp_enqueue_script( 'jquery-form' );
		
		// jQuery UI Core
		
		wp_enqueue_script( 'jquery-ui-core' );
		
		// Tabs
		
		wp_enqueue_script( 'jquery-ui-tabs' );
		
		// Shortcode
		
		add_shortcode( 'ops', array( $this, 'the_ops_func' ) );

		/**
		 * Check theme options page
		 */
			
		add_action( 'current_screen', array( $this, 'options_screen' ) );
		
		/**
		 * Google fonts
		 */
				
		add_action( 'wp_enqueue_scripts', array( $this, 'google_fonts_load' ) );
		
		/**
		 * Admin scripts and style
		 */
				
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
		
		/**
		 * Add Menu Item
		 */
		
		add_action( 'admin_menu', array( $this, 'add_menu_item' ) );
		
		/**
		 * Register settings via Wordpress API
		 */
		
		add_action( 'admin_init', array( $this, 'register_fields' ) );
				
		/**
		 * Register Ajax
		 */
		
		add_action( 'admin_enqueue_scripts', array( $this, 'register_ajax' ) );
		
   		/**
		 * Ajax callbacks
		 */
		 
		foreach ( $this->ajax as $type ) {
					
			$this->get_new_object( $type, $this->prefix, null );
			
			do_action( 'pof_' . $type . '_ajax' );

		}		
						
	}
	
	public function register_fields() {
		
		register_setting( $this->page, POF . $this->prefix, array( $this, 'sanitize_options' ) );

		if ( $this->fields ) {
			
			$simple_types_count = 0;
			
			$currentSection = null;
			
			foreach ( $this->fields as $o ) {
			
				$o = (object) $o;
				
				if ( $o ) {
																		
					if ( $o->type == 'section' ) {
																				
						add_settings_section(
							$o->id,
							null, 
							null,
							$this->page
						);
						
						$currentSection = $o->id;
						$this->sections[] = $o;
						
					} else {
						
						if ( $o->type == 'logo' && isset( $o->url ) ) {
							
							isset( $o->align ) ? $align = $o->align : $align = 'center';
							isset( $o->width ) ? $width = $o->width : $width = null;
							isset( $o->height ) ? $height = $o->height : $height = null;
							
							$this->set_logo( esc_url( $o->url ), $align, $width, $height );
														
						}
						
						if ( ! isset( $o->name ) ) {
							
							$simple_types_count++;
							
							$o->name = 'simple_type_' . $simple_types_count;
							
						}
						
						if ( $o->type == 'backup' ) $o->name = 'pof-backup';
						
						if ($o->type != 'logo' ) {
																						
							add_settings_field( 
								$o->name,
								null, 
								array( $this, 'create_field' ),
								$this->page, 
								$currentSection,
								array( $o->type, (array) $o, $this->options, $this->prefix )
							);
						
						}
															
					}
												
				}
				
			}
			
		}
	}
	
	/**
	 * Create options page
	 */
	
	public function options_page() {
		
		$main_css_class = 'pof-options';
		
		?>
		<div class="wrap <?php echo $main_css_class; ?>">
						
			<form action='options.php' method='post' id='pof-form' class='<?php echo 'pof' . $this->prefix; ?>' data-prefix='<?php echo 'settings_for' . $this->prefix; ?>'>
				
				<?php
				$this->print_options();
				?>
						
			</form>
		
		</div>
		<?php	
	}
	
	/**
	 * Create menu item
	 */
	
	public function add_menu_item() {
		
		$u_theme_menu = array(
			__( 'Theme Options', POF_LANG ),
			__( 'Theme Options', POF_LANG ),
			'dashicons-admin-generic' 
		);
		
		if ( isset( $this->menu_vars['name'] ) ) $u_theme_menu[0] = $this->menu_vars['name'];
		if ( isset( $this->menu_vars['title'] ) ) $u_theme_menu[1] = $this->menu_vars['title'];
		if ( isset( $this->menu_vars['icon'] ) ) $u_theme_menu[2] = $this->menu_vars['icon'];
		
		if ( $this->menu_top ) {
		
			add_menu_page( 
				$u_theme_menu[0], // Name
				$u_theme_menu[1], // Title
				'read', 
				$this->page, // Slug
				array( $this, 'options_page' ), 
				$u_theme_menu[2] // Icon
			);
			
		} else {
			
			add_theme_page( 
				$u_theme_menu[0], // Name
				$u_theme_menu[1], // Title
				'read', 
				$this->page, // Slug
				array( $this, 'options_page' ) 
			);	
		}
	}
	
	/**
	 * Wordpress ColorSchemes
	 */
	
	public function styles() {	

		// Colors of elements
		
		global $_wp_admin_css_colors; 
	
		$admin_colors = $_wp_admin_css_colors[get_user_option('admin_color')]->colors;
		
		$main_css_class = 'pof-options';
		
		$custom_css =	'.' . $main_css_class . ' .pof-button {background-color: ' . $admin_colors[3] . '}';
		$custom_css .=	'.' . $main_css_class . ' .pof-button:hover {background-color: ' . $admin_colors[1] . '}';
		$custom_css .=	'.' . $main_css_class . ' .line-scale-pulse-out-rapid > div {background-color: ' . $admin_colors[0] . '}';
		$custom_css .=	'.' . $main_css_class . ' .tabs-links li a {background-color:' . $admin_colors[1] . ' !important}';
		$custom_css .=	'.' . $main_css_class . ' .tabs-links li.ui-state-active a {background-color:' . $admin_colors[3] . ' !important}';

		wp_add_inline_style( 'pof-admin-css', $custom_css );

	}
	
	/**
	 * Hook for status bar
	 */
	 
	public function options_screen() {
	    
	    $currentScreen = get_current_screen();
	    	    	    
	    if( $currentScreen->id === 'toplevel_page_' . $this->page || $currentScreen->id === 'appearance_page_' . $this->page ) {
		    
			add_action( 'admin_bar_menu', array( $this, 'submit_top_button' ), 999 );
			
	   }
	}
	
	/**
	 * Add status bar in WP Admin Bar
	 */
	
	public function submit_top_button( $wp_admin_bar ) {
		
		if ( ! $this->status_bar_hidden ) {
			
			$args = array(
				'id' => 'pof-submit-top-button',
				'href' => '',
			);
			
			$wp_admin_bar->add_node($args);
		
		}
		
	}
	
	/**
	 * Register Ajax
	 */
	
	public function register_ajax() {
		
		$currentAjax = 'settings_for' . $this->prefix;
		
		wp_localize_script( 'pof-ajax', $currentAjax, array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'ajaxnonce' => wp_create_nonce( POF ),
			'ajaxprefix' => $this->prefix,
			'optionsSaving' => __( 'Options saving...', POF_LANG ),
			'successMessage' =>  __( 'Options updated', POF_LANG ),
			'errorMessage' => __( 'Error in system', POF_LANG ),
			'backupCreating' => __( 'Backup creating...', POF_LANG ),
			'backupCreated' => __( 'Backup created', POF_LANG ),
			'backupDeleting' => __( 'Backup deleting...', POF_LANG ),
			'backupDeleted' => __( 'Backup deleted', POF_LANG ),
			'optionsRestoring' => __( 'Options restoring...', POF_LANG ),
			'optionsRestored' => __( 'Options restored', POF_LANG ),
			'updatingBackups' => __( 'Updating backups list...', POF_LANG ),
			'updatedBackups' => __( 'Backups updated', POF_LANG ),
			'updatingOptions' => __( 'Updating options values...', POF_LANG ),
			'uploaderTitle' => __( 'Upload image', POF_LANG ),
			'uploaderText' => __( 'Upload', POF_LANG ),
			)
		);
	}
	
	/**
	 * Sanitize options from input after submit
	 *
	 * @param array $input Input option
	 * @return array Sanitized Input
	 */	
	
	public function sanitize_options( $input ) {
				
		$new_input = array();
													
		foreach ( $input as $key => $value ) {
						
			foreach ( $this->fields as $o ) {
				
				$o = ( object ) $o;
				
				if ( $o->name == $key ) {
						
					$class = $this->get_class_name( $o->type );
				
					if ( $this->class_loaded( $o->type ) ) {
									
						$temp_obj = new $class();
						
						$value = $temp_obj->sanitize( $value );
						
						unset( $temp_obj );
					
					}
					
					$new_input[ $key ] = $value;
					
				}
				
			}
			
		}
						
		return $new_input;
		
	}
	
	/**
	 * Register Google Fonts
	 */	
	
	public function google_fonts_load() {
		
		if ( $this->options ) {
			
			$fonts = array();
		
			$styles = array();
		
			foreach ( $this->options as $k => $o ) {
				
				if ( is_array( $o ) && isset( $o['face'] ) && $o['face'] != '0') {
					
					$font = explode( ',', $o['face'] );
					
					$fam = $font[0];
					
					$def = $font[1];
		
					$font = str_replace( " ", "+", $fam );
					
					$fontname = $font;
										
					if ( $o['weight'] != '0' ) $font .= ':' . $o['weight'];
										
					$fonts[] = $font;
	    								
					$custom_css = '.' . $k . '-google-font {font-family: "' .$fam . '",' . $def . ';font-size: ' . $o['size']. ';';
					
					if ( isset( $o['color'] ) && $o['color'] != '' ) {
					
						$custom_css .= 'color: ' . $o['color'];
					
					}
					
					$custom_css .= '}';
					
					$styles[] = $custom_css;
					
				}
				
			}
			
			$font = implode( '|', $fonts );
			
			if ( $font ) wp_enqueue_style( "pof-google-fonts", "//fonts.googleapis.com/css?family=$font", false, null, 'all' );
					
			foreach ( $styles as $s ) {
				
				wp_add_inline_style( "pof-google-fonts", $s );
	
			}
		
		}
		
	}
	
	/**
	 * Shortcode function
	 */	
	
	public function the_ops_func( $atts ) {
		
		$atts = array_change_key_case( ( array )$atts, CASE_LOWER );
		
		$the_atts = shortcode_atts( array(
			0 => false,
			1 => 'theme'
		), $atts, 'ops' );
			
		return get_ops( $the_atts[0], $the_atts[1] );
	}
	
	/* Private Methods */
	
	/**
	 * Print options
	 */
	
	private function print_options() {
		
		if ( is_array( $this->logo_vars ) && isset( $this->logo_vars[ 'url' ] ) ) { 
			
			$width = $height = '';
			
			if ( isset( $this->logo_vars[ 'width' ] ) ) $width = ' width="' . $this->logo_vars[ 'width' ] . '"';
			if ( isset( $this->logo_vars[ 'height' ]) ) $height = ' height="' . $this->logo_vars[ 'height' ] . '"'; 
		?>
			<div class="u_ops_align_<?php echo $this->logo_vars['align']; ?> logo-holder">
				<img class="u_ops_image" data-align="<?php echo $this->logo_vars[ 'align' ]; ?>" src="<?php echo $this->logo_vars[ 'url' ]; ?>"<?php echo $width.$height; ?> />
			</div>
			
		<?php }
		
		echo '<div id="sections">';
		
		$this->generate_tabs();
		settings_fields( $this->page );
		$this->do_settings_sections( $this->page );
		
		echo '</div>';
				
		if ( ! $this->starter ) {
		
			echo '<p class="submit" id="submit-options"><input type="submit" name="submit" id="submit" class="pof-button" value="' . __( 'Save', POF_LANG ) . '"></p>';
			
		}
		
	}

	/**
	 * Create tabs from sections
	 */
	
	private function generate_tabs() {
		
		$class = '';
		
		if ( $this->first_section_hidden ) {
		
			if ( count($this->sections) == 1 ) $class = ' class="u_ops_hidden_section"'; // Hide if only one section
		
		}
		
		if ( $this->sections ) {
						
			echo '<ul class="tabs-links">';
			
	        foreach ( $this->sections as $section ) {
		    	
		    	echo '<li' . $class . '><a href="#' . $section->id . '">' . $section->title . '</a></li>';   
		    }
		    
			echo '</ul>';	
		
		}
	}
	
	/* Setting */
	
	/**
	 * Custom markup for settings sections
	 *
	 * @param string $page Page
	 */
	
	private function do_settings_sections( $page ) {
				
		global $wp_settings_sections, $wp_settings_fields;
		
		if ( ! isset( $wp_settings_sections[$page] ) ) return;

        foreach ( (array) $wp_settings_sections[$page] as $section ) {
	                    
            if ( $section['callback'] ) call_user_func( $section['callback'], $section );
				 				 				 
			echo '<ul id="' . $section['id']. '" class="u_ops_section">';	 
			
			$this->do_settings_fields( $page, $section['id'] );
			
			echo '</ul>';
				 	                
        }
	}
	
	/**
	 * Custom markup for settings and fields
	 *
	 * @param string $page Page
	 * @param string $section Section
	 */
	
	private function do_settings_fields( $page, $section ) {
		
		global $wp_settings_fields;
		
		if ( ! isset( $wp_settings_fields[$page][$section] ) ) return;

        foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
	        
	        $type = $field['args'][1]['type'];
	        
	        $class = $this->get_class_name( $type );
	        
	        if ( $this->class_loaded( $type ) )	call_user_func($field['callback'], $class, $field['args'][1], $field['args'][2], $field['args'][3]);

        }

	}
	
	/* Fields */
	
	/**
	 * Get fields from file, check and generate ids for fields or create default section if error
	 *
	 * @return array Fields
	 */
	
	private function get_current_fields( $fields = null ) {
			
		if ( $fields && is_array( $fields ) ) {
			
			$check_fields = array_filter( $fields, function( $f ) { return ( $f['type'] == 'section' ); } );
			
			if ( $check_fields ) {
				
				$sections_count = 1;
				
				$fields_count = 1;
				
				$temp_fields = array();
				
				foreach ( $fields as $f ) {
					
					if ( $f['type'] == 'section' ) {
						
						$f['id'] = 'pof-section-' . $sections_count;
						
						$fields_count = 1;
						
						$sections_count++;
						
					} else if ( $f['type'] == 'logo' ) {
						
						$f['id'] = 'logo';
						
					} else {
						
						$f['id'] = 'pof-field-' . $fields_count . '-of-section-' . intval( $sections_count - 1 );
						
						$fields_count++;
						
					}
					
					$temp_fields[] = $f;
					
				}
				
				$fields = $temp_fields;
				
				return $fields;
				
			}
			
		}	
		
		$fields = array();
		
		$fields[] = array(
			'type' => 'logo',
			'url' => pof_get_uri() . 'logo.png'
		);	
		
		$fields[] = array(
			'type' => 'section',
			'id' => 'pof-section-1',
			'title' => 'Main'
		);

		$fields[] = array(
			'type' => 'html',
			'align' => 'center',
			'id' => 'html',
			'html' => __( '<p>Welcome to <b>Panda Options Framework</b> starter panel!</p><p>To start using this awesome product you should define your custom fields.</p><p>Please check this', POF_LANG ) . ' ' . '<a href="' . pof_get_uri() . 'documentation/">' . __( 'documentation', POF_LANG ) . '</a>' . ' ' . __('if you have issues with settings!</p>', POF_LANG )
		);
				
		$this->starter = true;
				
		return $fields;

	}

	/**
	 * Get current options or option by name
	 *
	 * @param string $name Name of option
	 * @return string|array|null Options or option by name or null
	 */

	private function get_true_options( $name = false ) {
		
		global $wpdb;
		
		$options = '';
						
		$result = $wpdb->get_results( "SELECT option_value FROM {$wpdb->options} WHERE option_name = '" . POF . $this->prefix . "'" );
		
		if ( $result ) {
		
			$options = $result[0]->option_value;
		
		}

		$options = unserialize( $options );
		
		if ( ! $name ) return $options;
		
		if ( isset( $options[$name] ) ) return $options[$name];
		
		return null;
	}
	
	/**
	 * Get field by key and value 
	 *
	 * @param string $key Name of field
	 * @param string $value Value of field
	 * @return object|null Object or null
	 */
	
	private function get_field_by( $key, $value ) {
		
		if ( ! $key || $key == '' || ! $value || $value == '' ) return false;
								
		foreach ( $this->fields as $o ) {
			
			if ( isset( $o[$key] ) && $o[$key] == $value ) return $o;
		}
		
		return false;
	}

	/**
	 * Create field object and output its html
	 *
	 * @param string $class Class of field object
	 * @param array $field Array of field details
	 * @param array $options Current values of field
	 * @param string $prefix Current options prefix
	 */
	
	private function create_field( $class, $field = null, $options = null, $prefix = null ) {
			
		$obj = new $class( $field, $options, $prefix );
								
		$obj->process();
		
		unset( $obj );
		
	}
	
	/**
	 * Load true class
	 *
	 * @param string $type Type of field
	 * @return bool Loaded or not
	 */
	
	private function class_loaded( $type ) {
		
		$class = $this->get_class_name( $type );
		
		$path = $this->get_class_path( $type );
		
		if ( file_exists( $path ) ) {

			if ( ! class_exists( $class ) ) require_once( $path );
			
			return true;
		
		}		
		
		return false;
		
	}
	
	/**
	 * Get true class name
	 *
	 * @param string $type Type of field
	 * @return string Class name
	 */	
	
	private function get_class_name( $type ) {
		
		return __CLASS__ . '_' . ucfirst( $type ) . '_Field';
	
	}
	
	/**
	 * Get true class path
	 *
	 * @param string $type Type of field
	 * @return string Class path
	 */	
	 
	private function get_class_path( $type ) {
				
		return trailingslashit( dirname( __FILE__ ) ) . 'fields/class.pof-' . $type . '-field.php';
	
	}
	
	/**
	 * Create new object to access its methods
	 *
	 * @param string $type Type of field
	 * @param string $prefix Current options prefix
	 * @param function $callback Callback function
	 */
	
	private function get_new_object( $type, $prefix = null, $callback = null ) {
		
		$field = $this->get_field_by( 'type', $type );
		
		if ( $field ) {
			
			$class = $this->get_class_name( $type );
			
			if ( $this->class_loaded( $type ) ) {
												
				$object = new $class( $field, null, $prefix );
								
				if ( $object && is_callable( $callback ) ) $callback( $object );
						
			}
			
		}
	}
	
}
?>
