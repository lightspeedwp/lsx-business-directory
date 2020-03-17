<?php
namespace lsx\business_directory\classes\admin;

/**
 * Houses the functions for the CMB2 Settings page.
 *
 * @package lsx-business-directory
 */
class Settings_Theme {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object \lsx\business_directory\classes\admin\Settings_Theme()
	 */
	protected static $instance = null;

	/**
	 * Will return true if this is the LSX BD settings page.
	 *
	 * @var array
	 */
	public $is_options_page = false;

	/**
	 * Holds the id and labels for the navigation.
	 *
	 * @var array
	 */
	public $navigation = array();

	/**
	 * Contructor
	 */
	public function __construct() {
		add_action( 'cmb2_before_form', array( $this, 'generate_navigation' ), 10, 4 );
		add_action( 'cmb2_before_title_field_row', array( $this, 'output_tab_open_div' ), 10, 1 );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return    object \lsx\business_directory\classes\admin\Settings_Theme()    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Generates the tabbed navigation for the settings page.
	 *
	 * @param string $cmb_id
	 * @param string $object_id
	 * @param string $object_type
	 * @param object $cmb2_obj
	 * @return void
	 */
	public function generate_navigation( $cmb_id, $object_id, $object_type, $cmb2_obj ) {
		if ( 'lsx_bd_settings' === $cmb_id && 'lsx-business-directory-settings' === $object_id && 'options-page' === $object_type ) {
			$this->navigation      = array();
			$this->is_options_page = true;
			if ( isset( $cmb2_obj->meta_box['fields'] ) && ! empty( $cmb2_obj->meta_box['fields'] ) ) {
				foreach ( $cmb2_obj->meta_box['fields'] as $field_index => $field ) {
					if ( 'title' === $field['type'] ) {
						$this->navigation[ $field_index ] = $field['name'];
					}
				}
			}
			$this->output_navigation();
		}
	}

	/**
	 * Outputs the WP style navigation for the Settings page.
	 *
	 * @return void
	 */
	public function output_navigation() {
		if ( ! empty( $this->navigation ) ) {
			?>
			<div class="wp-filter hide-if-no-js">
				<ul class="filter-links">
					<?php
					$first_tab = true;
					foreach ( $this->navigation as $key => $label ) {
						$current_css = '';
						if ( true === $first_tab ) {
							$first_tab   = false;
							$current_css = 'current';
						}
						?>
							<li><a href="#" class="<?php echo esc_attr( $current_css ); ?>" data-sort="<?php echo esc_attr( $key ); ?>_tab"><?php echo esc_attr( $label ); ?></a></li>
						<?php
					}
					?>
				</ul>
			</div>
			<?php
		}
	}

	/**
	 * Outputs the opening tab div.
	 *
	 * @param object $field CMB2_Field();
	 * @return void
	 */
	public function output_tab_open_div( $field ) {
		if ( true === $this->is_options_page && isset( $field->args['type'] ) && 'title' === $field->args['type'] ) {
			?>
			<div id="<?php echo esc_attr( $field->args['id'] ); ?>_tab">
			<?php
		}
	}
}
