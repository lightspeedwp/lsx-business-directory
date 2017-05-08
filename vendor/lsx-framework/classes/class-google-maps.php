<?php
/**
 * LSX Framework
 *
 * @package		LSX Framework
 * @author		LightSpeed Team
 * @license		GPL-2.0+
 * @copyright	2015  LightSpeed Team
 */

/**
 * A class that modules can use and extend.
 * @package		LSX Framework
 * @subpackage	classes
 * @category	maps
 */
class LSX_Maps {
	/**
	 * Holds class isntance
	 *
	 * @since 0.0.1
	 *
	 * @var      object|Lsx
	 */
	protected static $instance = null;

	/**
	 * Holds the google api key.
	 *
	 * @since 0.0.1
	 *
	 * @var      string
	 */
	protected $api_key = false;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since 0.0.1
	 *
	 * @access private
	 */
	private function __construct() {

		add_action('lsx_settings_module_tabs',array($this,'settings_tabs'));
		add_action('lsx_settings_module_templates',array($this,'settings_template'));

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		$options = Lsx_Options::get_single( 'lsx' );
		//if( false !== $options && is_array($options['lsx-tour-operators']) ){
		if(isset($options['gmaps_api_key']) && '' != $options['gmaps_api_key']){
			$this->api_key = $options['gmaps_api_key'];
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
	 * Settings Page Header.
	 */
	function settings_tabs(){
		echo '<a class="{{#is _current_tab value="#lsx-panel-gmaps"}}nav-tab-active {{/is}}lsx-nav-tab nav-tab" href="#lsx-panel-gmaps">' . __('Google Maps', 'lsx') . '</a>';
	}

	/**
	 * Settings page html
	 */
	function settings_template(){
	?>
		<div id="lsx-panel-gmaps" class="lsx-editor-panel" {{#is _current_tab value="#lsx-panel-gmaps"}}{{else}} style="display:none;" {{/is}}>
			<h4><?php _e('Google Maps', 'lsx') ; ?> <small class="description"><?php _e('API Key', 'lsx') ; ?></small></h4>
			<div class="lsx-config-group">
				<label for="lsx-gmaps-key">
					<?php _e( 'Google Maps API Key', 'lsx' ); ?>
				</label>
				<input type="text" name="gmaps_api_key" value="{{gmaps_api_key}}" id="lsx-gmaps-key">
			</div>
		</div>
	<?php
	}

	/**
	 * Register and enqueue front-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function enqueue_scripts() {
		if(false !== $this->api_key && is_singular('business-directory')){

			wp_enqueue_script('google_maps_api', 'https://maps.googleapis.com/maps/api/js?key='.$this->api_key.'&signed_in=true&libraries=places', array('jquery'), null, false);

			/*wp_enqueue_script('lsx_maps', LSX_FRAMEWORK_URL . 'assets/js/lsx-maps.js', array('jquery','google_maps_api'), null, true);
			 $param_array = array(
			 		'api_key' => $this->api_key
			 );
			wp_localize_script( 'lsx_maps', 'lsx_maps_params', $param_array );*/

		}
	}

	/**
	 * Register and enqueue front-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function map_output($search = false,$zoom = 18) {
		global $content_width;


		/*if(false !== $this->api_key && false !== $search ){
			$map = "<iframe
			src=\"https://www.google.com/maps/embed/v1/place
			?key={$this->api_key}
			&q={$search}
			&zoom={$zoom}\"
			width=\"{$content_width}\"
			height=\"400\"></iframe>";
			return $map;
		}*/

		if(false !== $this->api_key && false !== $search ){
			$map = '<div id="lsx-map" style="width:100%;height:400px;" data-address="'.$search.'"></div>';
			return $map;
		}
	}

}
global $lsx_maps;
$lsx_maps = LSX_Maps::get_instance();
