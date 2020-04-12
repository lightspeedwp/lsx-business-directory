<?php
namespace lsx\business_directory\classes\frontend;

/**
 * Google Maps Frontend
 *
 * @package lsx-business-directory
 */

class Google_Maps {
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
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );
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
		if ( null === self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	/**
	 * Sets the API Key value for use in the frontend.
	 * @return void
	 */
	public function init() {
		$this->api_key = lsx_bd_get_option( 'google_maps_api_key', false );
	}

	/**
	 * Register and enqueue front-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function enqueue_scripts() {
		if ( ! $this->is_a_bot() && is_singular( 'business-directory' ) && false !== $this->api_key ) {
			$address_tab_field = get_post_meta( get_the_ID(), 'address', true );
			if ( isset( $address_tab_field ) ) {
				if ( defined( 'SCRIPT_DEBUG' ) ) {
					$prefix = 'src/';
					$suffix = '';
				} else {
					$prefix = '';
					$suffix = '.min';
				}
				$has_thumbnail = $this->get_map_thumbnail();
				$dependacies   = array( 'jquery', 'lsx-bd-google-maps-api', 'lsx-bd-frontend' );
				$google_url    = 'https://maps.googleapis.com/maps/api/js?key=' . $this->api_key . '&libraries=places';
				if ( '' !== $has_thumbnail ) {
					$dependacies = array( 'jquery', 'lsx-bd-frontend' );
					$placeholder = true;
				} else {
					wp_enqueue_script( 'lsx-bd-google-maps-api', $google_url, array( 'jquery' ), LSX_BD_VER, false );
					$placeholder = false;
				}
				wp_enqueue_script( 'lsx-bd-frontend-maps', LSX_BD_URL . 'assets/js/' . $prefix . 'lsx-bd-frontend-maps' . $suffix . '.js', $dependacies, LSX_BD_VER, true );
				$param_array = array(
					'api_key'     => $this->api_key,
					'google_url'  => $google_url,
					'placeholder' => $placeholder,
				);
				wp_localize_script( 'lsx-bd-frontend-maps', 'lsx_bd_maps_params', $param_array );

			}
		}
	}

	/**
	 * Register and enqueue front-specific style sheet.
	 *
	 * @since 1.0.0
	 *
	 * @return    null
	 */
	public function render( $search = '', $zoom = 6 ) {
		if ( false !== $this->api_key && '' !== $search && '' !== $search ) {
			$map = '<div id="lsx-bd-map" class="lsx-bd-map" style="width:100%;" data-search="' . esc_attr( $search ) . '">';
			if ( false !== lsx_bd_get_option( 'google_maps_enable_placeholder', false ) ) {
				$map .= '<div class="lsx-bd-map-placeholder">';
				$map .= $this->get_map_thumbnail();
				$map .= '<div class="placeholder-text">' . esc_attr__( 'View map', 'lsx-business-directory' ) . '</div>';
				$map .= '</div>';
			}
			$map .= '</div>';
			return $map;
		}
	}

	/**
	 * Get the map thumbnail for the Google Maps.
	 *
	 * @return string
	 */
	public function get_map_thumbnail( $size = 'lsx-thumbnail-carousel' ) {
		$image         = '';
		$image_src     = '';
		$map_thumbnail = get_post_meta( get_the_ID(), 'lsx_bd_map_thumbnail_id', true );
		if ( false === $map_thumbnail || '' === $map_thumbnail ) {
			// Lets look for a general placeholder.
			$general_map_placeholder = lsx_bd_get_option( 'google_maps_placeholder_id', false );
			if ( false !== $general_map_placeholder ) {
				$src_array = wp_get_attachment_image_src( $general_map_placeholder, $size );
				if ( is_array( $src_array ) && isset( $src_array[0] ) ) {
					$image_src = $src_array[0];
				}
			}
			if ( '' === $image_src ) {
				$image_src = \lsx\business_directory\includes\get_placeholder( $size, 'google_maps_placeholder', 'map-' );
			}
			$image     = '<img src="' . $image_src . '" class="attachment-thumbnail size-thumbnail" alt="">';
		} elseif ( false !== $map_thumbnail && '' !== $map_thumbnail ) {
			$image = wp_get_attachment_image( $map_thumbnail, $size );
		}
		return $image;
	}

	/**
	 * Checking for bots on the maps.
	 *
	 * @package  ctt-lsx-child
	 */
	public function is_a_bot() {
		$is_bot      = false;
		$user_agents = array(
			'Googlebot',
			'Googlebot-Mobile',
			'Googlebot-Image',
			'Googlebot-News',
			'Googlebot-Video',
			'AdsBot-Google',
			'AdsBot-Google-Mobile-Apps',
			'Feedfetcher-Google',
			'Mediapartners-Google',
			'APIs-Google',
			'GTmetrix',
			'Baiduspider',
			'ia_archiver',
			'R6_FeedFetcher',
			'NetcraftSurveyAgent',
			'Sogou web spider',
			'bingbot',
			'BingPreview',
			'slurp',
			'Yahoo! Slurp',
			'Ask Jeeves/Teoma',
			'facebookexternalhit',
			'PrintfulBot',
			'msnbot',
			'Twitterbot',
			'UnwindFetchor',
			'urlresolver',
			'Butterfly',
			'TweetmemeBot',
			'PaperLiBot',
			'MJ12bot',
			'AhrefsBot',
			'Exabot',
			'Ezooms',
			'YandexBot',
			'SearchmetricsBot',
			'picsearch',
			'TweetedTimes Bot',
			'QuerySeekerSpider',
			'ShowyouBot',
			'woriobot',
			'merlinkbot',
			'BazQuxBot',
			'Kraken',
			'SISTRIX Crawler',
			'R6_CommentReader',
			'magpie-crawler',
			'GrapeshotCrawler',
			'PercolateCrawler',
			'MaxPointCrawler',
			'R6_FeedFetcher',
			'NetSeer crawler',
			'grokkit-crawler',
			'SMXCrawler',
			'PulseCrawler',
			'Y!J-BRW',
			'80legs.com/webcrawler',
			'Mediapartners-Google',
			'Spinn3r',
			'InAGist',
			'Python-urllib',
			'NING',
			'TencentTraveler',
			'Feedfetcher-Google',
			'mon.itor.us',
			'spbot',
			'Feedly',
			'bitlybot',
			'ADmantX Platform',
			'Niki-Bot',
			'Pinterest',
			'python-requests',
			'DotBot',
			'HTTP_Request2',
			'linkdexbot',
			'A6-Indexer',
			'Baiduspider',
			'TwitterFeed',
			'Microsoft Office',
			'Pingdom',
			'BTWebClient',
			'KatBot',
			'SiteCheck',
			'proximic',
			'Sleuth',
			'Abonti',
			'(BOT for JCE)',
			'Baidu',
			'Tiny Tiny RSS',
			'newsblur',
			'updown_tester',
			'linkdex',
			'baidu',
			'searchmetrics',
			'genieo',
			'majestic12',
			'spinn3r',
			'profound',
			'domainappender',
			'VegeBot',
			'terrykyleseoagency.com',
			'CommonCrawler Node',
			'AdlesseBot',
			'metauri.com',
			'libwww-perl',
			'rogerbot-crawler',
			'MegaIndex.ru',
			'ltx71',
			'Qwantify',
			'Traackr.com',
			'Re-Animator Bot',
			'Pcore-HTTP',
			'BoardReader',
			'omgili',
			'okhttp',
			'CCBot',
			'Java/1.8',
			'semrush.com',
			'feedbot',
			'CommonCrawler',
			'AdlesseBot',
			'MetaURI',
			'ibwww-perl',
			'rogerbot',
			'MegaIndex',
			'BLEXBot',
			'FlipboardProxy',
			'techinfo@ubermetrics-technologies.com',
			'trendictionbot',
			'Mediatoolkitbot',
			'trendiction',
			'ubermetrics',
			'ScooperBot',
			'TrendsmapResolver',
			'Nuzzel',
			'Go-http-client',
			'Applebot',
			'LivelapBot',
			'GroupHigh',
			'SemrushBot',
			'ltx71',
			'commoncrawl',
			'istellabot',
			'DomainCrawler',
			'cs.daum.net',
			'StormCrawler',
			'GarlikCrawler',
			'The Knowledge AI',
			'getstream.io/winds',
			'YisouSpider',
			'archive.org_bot',
			'semantic-visions.com',
			'FemtosearchBot',
			'360Spider',
			'linkfluence.com',
			'glutenfreepleasure.com',
			'Gluten Free Crawler',
			'YaK/1.0',
			'Cliqzbot',
			'app.hypefactors.com',
			'axios',
			'semantic-visions.com',
			'webdatastats.com',
			'schmorp.de',
			'SEOkicks',
			'DuckABot',
			'AOLBuild',
			'Barkrowler',
			'ZoominfoBot',
			'Linguee Bot',
			'Mail.RU_Bot',
			'OnalyticaBot',
			'Linguee Bot',
			'admantx-adform',
			'Buck/2.2',
			'Barkrowler',
			'Zombiebot',
			'Nutch',
			'SemanticScholarBot',
			"Jetslid'e",
			'scalaj-http',
			'XoviBot',
			'sysomos.com',
			'PocketParser',
			'newspaper',
			'serpstatbot',
		);

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}
		foreach ( $user_agents as $agent ) {
			if ( strtolower( $agent ) === strtolower( $user_agent ) ) {
				$is_bot = true;
			}
		}
		return $is_bot;
	}
}
