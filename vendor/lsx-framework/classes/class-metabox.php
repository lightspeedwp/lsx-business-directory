<?php
/**
 * LST MetaBox
 *
 * @package   Lsx metabox
 * @author     LightSpeed Team
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015  LightSpeed Team
 */

/**
 * Plugin class.
 * @package Lsx_metabox
 * @author   LightSpeed Team
 */
class Lsx_Metabox {

	/**
	 * The slug for this plugin
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'lsx';

	/**
	 * List of metaboxes for registered
	 *
	 * @since 0.0.1
	 *
	 * @var      array
	 */
	protected $metaboxes = array();

	/**
	 * Holds class isntance
	 *
	 * @since 0.0.1
	 *
	 * @var      object|Lsx
	 */
	protected static $instance = null;

	/**
	 * Holds the option screen prefix
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_stylescripts' ) );
		
		// register metaboxes
		add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );

		// hook into saving metaboxes
		add_action( 'save_post', array( $this, 'save_metabox_data' ) );

		// filter to create field types
		add_filter('lsx_metabox_field_types', array( $this, 'get_field_types' ) );
	}
	

	/**
	 * get field types
	 *
	 * @uses lsx_metabox_field_types filter
	 * @since 0.0.1
	 * @return field types array
	 */
	public function get_field_types( $field_types ) {

		$internal_types = array(
			'places'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/places/field.php',
				'scripts'	=> array(
					'https://maps.googleapis.com/maps/api/js?libraries=places'
				),
			),
			'text'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/text/field.php'
			),				
			'textarea'		=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/textarea/field.php',
			),
			'select'		=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/select/field.php',
					'styles'	=> array(
							LSX_FRAMEWORK_URL . 'assets/css/select2.css',
					),
					'scripts'	=> array(
							LSX_FRAMEWORK_URL . 'assets/js/select2.min.js',
					)					
			),
			'radio'		=>	array(
					'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/radio/field.php',
			),				
			'country'		=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/country/field.php',
				'styles'	=> array(
					LSX_FRAMEWORK_URL . 'assets/css/select2.css',
				),
				'scripts'	=> array(
					LSX_FRAMEWORK_URL . 'assets/js/select2.min.js',
				),

			),
			'geo'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/geo/field.php',
			),
			'hours'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/hours/field.php',
			),
			'image'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/image/field.php',
				'handler'	=>	array( $this, 'handle_image' ),
				'scripts'	=> array(
					LSX_FRAMEWORK_URL . 'includes/fields/image/image-picker.js',
				),
			),
			'color'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/color/field.php',
				'styles'	=> array(
					'wp-color-picker'
				),
				'scripts'	=> array(
					'wp-color-picker'
				),
			),
			'date'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/date/field.php',
				'styles'	=> array(
					LSX_FRAMEWORK_URL . 'includes/fields/date/datepicker.css'
				),
				'scripts'	=> array(
					LSX_FRAMEWORK_URL . 'includes/fields/date/bootstrap-datepicker.js'
				),
			),
			'html'			=>	array(
				'file'		=>	LSX_FRAMEWORK_PATH . 'includes/fields/html/field.php',
			),			
		);

		return array_merge( $internal_types, $field_types );
	}

	/**
	 * handle the image seletion
	 *
	 * @since 0.0.1
	 * @return field value
	 */
	public function handle_image( $value ) {
		// check if is json selction type
		if( !empty( $value['selection'] ) ){
			if( is_string( $value['selection'] ) ){
				$is_json = json_decode( stripslashes_deep( $value['selection'] ), ARRAY_A );
				if( is_array( $is_json ) && !empty( $is_json ) ){
					$value['selection'] = $is_json;
				}
			}
		}
		return $value;
	}

	/**
	 * Save metabox data
	 *
	 * @since 0.0.1
	 *
	 */
	public function save_metabox_data( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['lsx-metabox'] ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
			return;
		}

		// get metaboxes for this post type
		$metaboxes = apply_filters( 'lsx_get_metaboxes', $this->metaboxes );

		if( empty( $metaboxes[ $_POST['post_type'] ] ) ){
			return;
		}

		// setup handler filters
		$field_types = apply_filters( 'lsx_metabox_field_types', array() );
		foreach( $field_types as $field_type => $setup ){
			if( isset( $setup['handler'] ) ){
				add_filter( 'lsx_handle_field-' . $field_type, $setup['handler'] );
			}
		}

		$metaboxes = $metaboxes[ $_POST['post_type'] ];
		// cool- lets go over each one and do stuff.
		foreach( $metaboxes as $metabox_slug => $metabox ){
			
			// Verify that the nonce is valid.
			if ( empty( $_POST['lsx-metabox'][ $metabox_slug ] ) && !wp_verify_nonce( $_POST['lsx-metabox'][ $metabox_slug ]['nonce'], 'lsx-metabox-' . $metabox_slug ) ) {
				continue;
			}
			// get clean structure
			$metabox_structure = json_decode( stripslashes_deep( $_POST['lsx-metabox'][ $metabox_slug ]['instance'] ), ARRAY_A );
			// update the core structure for UI
			update_post_meta( $post_id, '_' . $metabox_slug . '_data', $metabox_structure );

			// get panels/
			$panels = $this->order_panels( $metabox['panels'] );

			// righto! lets get to work.
			foreach( $panels as $tab_slug => $tab ){
				// each panel gets worked on 
				foreach( $tab['sections'] as $section_slug => $section_panel ){
					// each section gets checked and saved
					foreach( $section_panel['parts'] as $section_index => $section ){
						// can use do this?
						if( !empty( $section['capability'] ) ){
							if ( ! current_user_can( $section['capability'] ) ) {
								continue;
							}
						}
						// clear out field data
						foreach( $section['fields'] as $field_slug => $field ){
							// remove all ( hack I know, I should get all then update and remove only those that should be... later )
							delete_post_meta( $post_id, $field_slug );
						}

						// build section data array
						// This sotres a semi-compiled array for easy use in loops etc.
						$section_array = array();
						if( !empty( $_POST[ $section_index ] ) ){

							foreach( $_POST[ $section_index ] as $part_id => $part ){
								// the prearray for this section combined list.
								$section_pre_array = array();
								// single instance of this part
								foreach( $section['fields'] as $field_slug => $field ){
									
									if( empty( $part[ $field_slug ] ) ){
										continue;
									}
									foreach( $part[ $field_slug ] as $field_entry ){
										
										// handle and sanitize
										$value = apply_filters( 'lsx_handle_field-' . $field_type, $field_entry['value'], $post_id );

										if( isset( $field['sanitize_callback'] ) ){

											add_filter( 'lsx_sanitize_value-' . $field_slug, $field['sanitize_callback'], 10, 2 );
											$value = apply_filters( 'lsx_sanitize_value-' . $field_slug, $value, $post_id );
											remove_filter( 'lsx_sanitize_value-' . $field_slug, $field['sanitize_callback'] );

										}
										if( !empty( $field['repeatable'] ) ){
											$section_pre_array[ $field_slug ][] = $value;
										}else{
											$section_pre_array[ $field_slug ] = $value;
										}
										
										/*
										//If its a relation,  delete any old relation it had,  then update with a new one, pretty much exactly the same data.
										if( !empty( $section['relation'] ) ){
											delete_post_meta( $post_id, $field_slug,$post_id );
										}*/
										
										
										// save the post meta
										add_post_meta( $post_id, $field_slug, $value );
									}
								}
								if( !empty( $section['repeatable'] ) ){
									$section_array[] = $section_pre_array;
								}else{
									$section_array = $section_pre_array;
								}
							}
						}
						// update the section array
						update_post_meta( $post_id, $section_index, $section_array );
					}
				}
			}
		}

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return    object|Lsx    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Registeres a metabox
	 *
	 * @since 0.0.1
	 *
	 */
	public function register_metabox( $args ) {

		// metabox structure:
		// post_type
		//  slug (main box)
		//	- panel ( side tab )
		//		- section ( section heading in tab body )
		//		 - section ( section priority (first definition wins) )
		//		- field
		//		 - field priority ( first definition wins )

		if( empty( $args['post_type'] ) || empty( $args['id'] ) ){
			return;
		}
		$post_types = (array) $args['post_type'];
		// set slug
		if( !empty( $args['name'] ) ){
			$slug = sanitize_key( $args['name'] );
		}
		// panel
		$panel = sanitize_key( $args['panel'] );

		foreach( $post_types as $post_type ){
			if( empty( $this->metaboxes[ $post_type ][ $slug ] ) ){
				$this->metaboxes[ $post_type ][ $slug ] = array(
					'name' 		=> 	$args['name'],
					'context'	=>	$args['context'],
					'priority'	=>	$args['priority'],			
					'panels'	=>	array()
				);
			}
			// add to array / no overides here.
			if( !isset( $this->metaboxes[ $post_type ][ $slug ]['panels'][ $panel ][ $args['id'] ] ) ){
				$this->metaboxes[ $post_type ][ $slug ]['panels'][ $panel ][ $args['id'] ] = $args; // sections are ordered on render.
			}
		}

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( $this->plugin_slug, FALSE, basename( LSX_FRAMEWORK_PATH ) . '/languages');

	}

	
	/**
	 * Adds registered metaboxes to wordpress
	 *
	 * @since 0.0.1
	 *
	 */
	public function add_metaboxes() {

		$metaboxes = apply_filters( 'lsx_get_metaboxes', $this->metaboxes );

		$screen = get_current_screen();
		if( !is_object( $screen ) || $screen->base != 'post' || empty( $metaboxes[ $screen->post_type ] ) ){
			return;
		}

		// enqueue the styles and scripts for metaboxes
		wp_enqueue_script( 'lsx-metabox', LSX_FRAMEWORK_URL . 'assets/js/metabox.js', array( 'jquery' ), LSX_FRAMEWORK_VERSION, true );
		wp_enqueue_style( 'lsx-metabox', LSX_FRAMEWORK_URL . 'assets/css/metabox.css');

		// load fieldtype support
		$field_types = apply_filters( 'lsx_metabox_field_types', array() );
		foreach($field_types as $field_type_slug=>$field_type){
			//enqueue styles
			if( !empty( $field_type['styles'])){
				foreach($field_type['styles'] as $style){
					if( false !== strpos($style, '//')){
						wp_enqueue_style( $field_type_slug . '-' . sanitize_key( basename( $style ) ), $style, array(), LSX_FRAMEWORK_VERSION );
					}else{
						wp_enqueue_style( $style );
					}
				}
			}

			//enqueue scripts
			if( !empty( $field_type['scripts'])){
				// check for jquery deps
				$depts[] = 'jquery';
				foreach($field_type['scripts'] as $script){
					if( false !== strpos($script, '//')){
						wp_enqueue_script( $field_type_slug . '-' . sanitize_key( basename( $script ) ), $script, $depts, LSX_FRAMEWORK_VERSION );
					}else{
						wp_enqueue_script( $script );
					}
				}
			}
		}

		// post type metaboxes
		foreach( $metaboxes as $post_type=>$metabox ){
			
			// only process this post type
			if( $post_type !== $screen->post_type ){
				continue;
			}
			
			// each metaboxs for this type
			foreach( $metabox as $slug=>$config ){		
				
				add_meta_box(
					$slug,
					$config['name'],
					array( $this, 'render_metabox' ),
					$screen->post_type,
					$config['context'],
					$config['priority'],
					$config
				);

				// here do the enqueue field types scripts and styles ( if any )


			}


		}

	}
	/**
	 * Renders Metaboxes
	 *
	 * @since 0.0.1
	 *
	 */
	public function render_metabox( $post, $metabox ) {
		$metabox['args']['id'] = $metabox['id'];
		$wrap_class = null;
		if( count( $metabox['args']['panels'] ) > 1 ){
			$wrap_class = " has-tabs";
		}

		// create array of panels
		$panels = $this->order_panels( $metabox['args']['panels'] );
		// build data object
		$instance = array();
		foreach( $panels as $panel_id => $panel ){
			foreach( $panel['sections'] as $section_id => $section ){
				
				foreach( (array) $section['parts'] as $part_id => $part) {
					$part_data = get_post_meta( $post->ID, $part_id, true );

					if( !empty( $part['capability'] ) && !user_can( wp_get_current_user(), $part['capability'] ) ){
						continue;
					}
					if( empty( $instance['_active_tab'] ) ){
						$instance['_active_tab'] = $metabox['id'] . '_tab_' . $panel_id;
					}


					if( !empty( $part_data ) ){
						if( empty( $part['repeatable'] ) ){
							$part_data = array(
								$part_data
							);
						}
						foreach( (array) $part_data as $entry_set ){
							$node_id = uniqid('nd');
							$node_point = array(
								'_id'			=>	$node_id,
								'_node_point' 	=> $part_id . '.' . $node_id,
							);
							$instance[ $part_id ][ $node_id ] = $node_point;

							foreach( (array) $entry_set as $field_slug => $field_entry ){
								if( !isset( $part['fields'][ $field_slug ] ) ){
									continue;
								}
								$field = $part['fields'][ $field_slug ];
								if( !empty( $field['capability'] ) && !user_can( wp_get_current_user(), $field['capability'] ) ){
									continue;
								}

								if( empty( $field['repeatable'] ) ){
									$field_entry = array(
										$field_entry
									);
								}

								foreach( (array) $field_entry as $field_value ){
									$entry_id = uniqid('nd');
									$instance[ $part_id ][ $node_id ][ $field_slug ][ $entry_id ] = array(
										'_id' 			=> $entry_id,
										'_node_point'	=> $part_id . '.' . $node_id . '.' . $field_slug . '.' . $entry_id,
										'value'			=>	$field_value
									);
									if( empty( $field['repeatable'] ) ){
										// stop repeats if altered.
										break;
									}
								}
							}
							if( empty( $part['repeatable'] ) ){
								// break repeats if altered
								break;
							}
						}						
					}else{
						$node_id = uniqid('nd');
						$node_point = array(
							'_id'			=>	$node_id,
							'_node_point' 	=> $part_id . '.' . $node_id,
						);
						$instance[ $part_id ][ $node_id ] = $node_point;
					}
				}
			}
		}

		echo '<input type="hidden" name="lsx-metabox[' . $metabox['id'] . '][nonce]" value="' . wp_create_nonce( 'lsx-metabox-' . $metabox['id'] ) . '">';
		?>
		<input type="hidden" autocomplete="off" name="lsx-metabox[<?php echo $metabox['id']; ?>][instance]" class="lsx-baldrick" id="lsx-metabox-<?php echo $metabox['id']; ?>-data" value="<?php echo esc_attr( json_encode( $instance ) ); ?>"
			data-request="#lsx-metabox-<?php echo $metabox['id']; ?>-data"
			data-target="#lsx-metabox-<?php echo $metabox['id']; ?>-ui"
			data-type="json"
			data-autoload="true"
			data-event="sync"
			data-callback="lsx_canvas_init"
			data-complete="lsx_resize_<?php echo $metabox['id']; ?>"
			data-template="#lsx-metabox-<?php echo $metabox['id']; ?>-template"
		>
		<div class="lsx-metabox-wrapper<?php echo $wrap_class; ?>" data-app="lsx-metabox-<?php echo $metabox['id']; ?>-data" id="lsx-metabox-<?php echo $metabox['id']; ?>-ui"></div>
		<script type="text/html" id="lsx-metabox-<?php echo $metabox['id']; ?>-template">
		<?php include LSX_FRAMEWORK_PATH . 'includes/templates/main-metabox.php'; ?>
		</script>
		<script>
		function lsx_resize_<?php echo $metabox['id']; ?>( obj ){
			
			var box = jQuery('#lsx-metabox-<?php echo $metabox['id']; ?>-ui'),
				inside = box.parent(),
				tabs = box.find('span.lsx-metabox-wrapper');

			inside.css( { minHeight : tabs.outerHeight() } );

		};
		</script>
		<?php
	}	


	/**
	 * Orders panels for metabox tabs
	 *
	 * @since 0.0.1
	 *
	 * @return    ordered array
	 */
	public function order_panels( $panels ) {

		$panels_pre_ordered = array();
		foreach( (array) $panels as $tab_slug => $tabs ){
			$array_keys = array_keys( $tabs );
			$panels_pre_ordered[ $tab_slug ] = (float) $tabs[ $array_keys[0] ]['panel_priority'];
		}

		$panels_pre_ordered = array_reverse( $panels_pre_ordered );
		// sort
		asort($panels_pre_ordered);

		// finial output
		$panels_ordered = array();
		foreach( $panels_pre_ordered as $tab_slug => $order ){
			$array_keys = array_keys( $panels[ $tab_slug ] );
			$title = false;
			if( !empty( $panels[ $tab_slug ][ $array_keys[0] ]['panel'] ) ){
				$title = $panels[ $tab_slug ][ $array_keys[0] ]['panel'];
			}
			$panels_ordered[$tab_slug] = array(
				'name'		=>	$title,
				'sections'	=>	$this->order_sections( $panels[ $tab_slug ] ) // sort the sections
			);
		}
		
		return $panels_ordered;

	}

	/**
	 * Orders sections for metabox tab panels
	 *
	 * @since 0.0.1
	 *
	 * @return    ordered array
	 */
	public function order_sections( $sections ) {
		global $post;

		$managed_sections = array();
		$sections_pre_ordered = array();
		foreach( (array) $sections as $section_index => $section ){
			$section_slug = '_base_section_';
			if( !empty( $section['section'] ) ){
				$section_slug = sanitize_key( $section['section'] );
			}
			// create a new key based section array
			$managed_sections[ $section_slug ][ $section['id'] ] = $section;
			// add sections for sorting
			if( !isset( $sections_pre_ordered[ $section_slug ] ) ){
				$sections_pre_ordered[ $section_slug ] = (float) $section['section_priority'];
			}
			// add fields & data
			if( !empty( $section['fields'] ) ){
				foreach( $section['fields'] as $field_slug => $field ){
					$managed_sections[ $section_slug ][ $section['id'] ]['fields'][ $field_slug ]['slug'] = $field_slug;
					if( empty( $field['type'] ) ){
						$managed_sections[ $section_slug ][ $section['id'] ]['fields'][ $field_slug ]['type'] = 'text';						
					}
					if( !empty( $field['default'] ) ){
						$raw_instance_data = (array) $field['default'];
					}else{
						$raw_instance_data = array('');
					}
					foreach( $raw_instance_data as $data ){
						// generate an ID for data line
						$id = uniqid('lsx_entry');
						$instance_data[ $id ] = $data;
					}
					$managed_sections[ $section_slug ][ $section['id'] ]['fields'][ $field_slug ]['data'] = $instance_data;
				}
			}
		}
		$sections_pre_ordered = array_reverse( $sections_pre_ordered );
		// sort
		asort($sections_pre_ordered);
		
		// finial output
		$sections_ordered = array();
		foreach( $sections_pre_ordered as $section_slug => $order ){
			$array_keys = array_keys( $managed_sections[ $section_slug ] );
			$title = $managed_sections[ $section_slug ][ $array_keys[0] ]['panel'];
			if( !empty( $managed_sections[ $section_slug ][ $array_keys[0] ]['section'] ) ){
				$title = $managed_sections[ $section_slug ][ $array_keys[0] ]['section'];
			}
			$sections_ordered[$section_slug] = array(
				'name'		=>	$title,
				'parts'		=>	$managed_sections[ $section_slug ]
			);
		}

		return $sections_ordered;

	}


	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 0.0.1
	 *
	 * @return    null
	 */
	public function enqueue_admin_stylescripts() {

		$screen = get_current_screen();

		if( !is_object( $screen ) ){
			return;
		}

		
		
		if( false !== strpos( $screen->base, 'post' ) ){

			wp_enqueue_style( 'lsx-metabox-forms', LSX_FRAMEWORK_URL . '/assets/css/form.css' );
			wp_enqueue_script( 'lsx-wp-baldrick', LSX_FRAMEWORK_URL . '/assets/js/wp-baldrick-full.js', array( 'jquery' ) , false, true );
			wp_enqueue_script('tiny_mce');
		}
	}
}