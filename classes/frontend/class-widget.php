<?php
/**
 * @package   lsx\business_directory\classes\frontend;
 */

namespace lsx\business_directory\classes\frontend;

/**
 * Setup plugin class.
 *
 * @package lsx\business_directory\classes\frontend
 * @author  LightSpeed
 */
class Widget {

	/**
	 * Holds class instance
	 *
	 * @since 1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Holds the boolean if this is a shortcode loop or not
	 *
	 * @var      object
	 */
	protected $is_shortcode = false;

	/**
	 * Holds the defaults for the current shortcode.
	 *
	 * @var array
	 */
	public $defaults = array(
		'carousel'         => false,
		'columns'          => 3,
		'slides_to_scroll' => 3,
		'custom_css'       => '',
		'title_text'       => '',
		'title_link'       => '',
		'taxonomy'         => '',
		'terms'            => '',
		'posts_per_page'   => 9,
		'post__not_in'     => '',
		'post__in'         => '',
		'order'            => '',
		'orderby'          => '',
		'hide_meta'        => false,
		'post_type'        => 'business-directory',
		'content_type'     => 'post',
		'template'         => 'single-col-business',
		'excerpt'          => false,
	);

	/**
	 * Holds the arguments for the current shortcode.
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * Holds the current query
	 *
	 * @var \WP_Query() | false
	 */
	public $query = false;

	/**
	 * Holds the current html being built
	 *
	 * @var string
	 */
	public $html = '';

	/**
	 * Holds the current index of the post being viewed
	 *
	 * @var int
	 */
	public $counter = 0;

	/**
	 * Return an instance of this class.
	 *
	 * @return    object \lsx\business_directory\classes\frontend\Widget();    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Returns the Widget HTML
	 *
	 * @return string
	 */
	public function render( $args = array() ) {
		$this->args              = wp_parse_args( $args, $this->defaults );
		$this->args['col_class'] = 12 / (int) $this->args['columns'];
		if ( 'post' === $this->args['content_type'] ) {
			$this->query_post_type();
		} else {
			$this->query_terms();
		}
		if ( $this->has_items() ) {
			$this->start_loop();
			$this->run_loop();
			$this->end_loop();
		}
		return $this->html;
	}

	/**
	 *  Runs a WP_Query() for your members.
	 */
	public function query_post_type() {

		$post_type = $this->args['post_type'];
		$post_type = explode( ',', $post_type );
		if ( ! is_array( $post_type ) ) {
			$post_type = array( $post_type );
		}

		$query_args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $this->args['posts_per_page'],
		);

		if ( '' !== $this->args['order'] ) {
			$query_args['order'] = $this->args['order'];
		}

		if ( '' !== $this->args['orderby'] ) {
			switch ( $this->args['orderby'] ) {
				case 'featured':
					$query_args['meta_query'][] = array(
						'key'     => 'lsx_be_featured',
						'value'   => '1',
						'compare' => '=',
					);
					break;

				case 'recent':
					$query_args['orderby'] = 'date';
					$query_args['order']   = 'desc';
					break;

				default:
					$query_args['orderby'] = $this->args['orderby'];
					break;
			}
		}

		if ( '' !== $this->args['post__not_in'] ) {
			$query_args['post__not_in'] = $this->args['post__not_in'];
		}

		if ( '' !== $this->args['taxonomy'] && '' !== $this->args['terms'] ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $this->args['taxonomy'],
					'terms'    => $this->args['terms'],
				),
			);
		}

		$this->query = new \WP_Query( $query_args );
	}

	/**
	 *  Runs a WP_Query() for your members.
	 */
	public function query_terms() {
		if ( '' === $this->args['order'] ) {
			$this->args['order'] = 'asc';
		}
		$args = array(
			'taxonomy'   => $this->args['taxonomy'],
			'number'     => (int) $this->args['posts_per_page'],
			'orderby'    => $this->args['orderby'],
			'order'      => $this->args['order'],
			'hide_empty' => 0,
		);

		if ( '' !== $this->args['post__in'] ) {
			$args = array(
				'include' => explode( ',', $this->args['post__in'] ),
				'orderby' => 'include',
			);
		}

		if ( 'none' !== $this->args['orderby'] ) {
			$args['suppress_filters']           = true;
			$args['disabled_custom_post_order'] = true;
		}

		$this->query = get_terms( $args );
	}

	/**
	 * Checks to see it the current query found any items
	 *
	 * @return boolean
	 */
	public function has_items() {
		$has_items = false;
		if ( false !== $this->query ) {
			if ( 'post' === $this->args['content_type'] && $this->query->have_posts() ) {
				$has_items = true;
			} elseif ( ! empty( $this->query ) && ! is_wp_error( $this->query ) ) {
				$has_items = true;
			}
		}
		return $has_items;
	}

	/**
	 * Create the Opening tags before the loop
	 */
	public function start_loop() {
		$this->is_shortcode = true;

		// This is the wrapper for the shortcode.
		$this->html = '<div class="lsx-business-directory-shortcode ' . $this->args['custom_css'] . '">';

		$this->title();

		// This outputs a carousel or a row.
		if ( 'true' === $this->args['carousel'] || true === $this->args['carousel'] ) {
			$this->html .= '<div class="lsx-business-directory-slider lsx-slick-slider" data-lsx-slick="{\"slidesToShow\": ' . $this->args['columns'] . ', \"slidesToScroll\": ' . $this->args['columns'] . ' }">';
		} else {
			$this->html .= '<div class="row row-flex">';
		}
	}

	/**
	 * Sets the title for the widget
	 */
	public function title() {
		if ( '' !== $this->args['title_text'] ) {
			$this->html .= '<h2 class="section-title lined lsx-title">';
			if ( '' !== $this->args['title_link'] ) {
				$this->html .= '<a href="' . $this->args['title_link'] . '">';
			}
			$this->html .= $this->args['title_text'];
			if ( '' !== $this->args['title_link'] ) {
				$this->html .= '</a>';
			}
			$this->html .= '</h2>';
		}
	}

	/**
	 * Runs through the query items
	 */
	public function run_loop() {
		$this->counter = 0;
		if ( 'post' === $this->args['content_type'] ) {
			while ( $this->query->have_posts() ) {
				$this->counter++;
				$this->query->the_post();
				ob_start();
				lsx_business_template( $this->args['template'] );
				$this->html .= ob_get_clean();
				$this->loop_bottom();
			}
		} else {
			global $item_term;
			$item_term = false;
			foreach ( $this->query as $item_term ) {
				$this->counter++;
				$item_term->args = $this->args;
				ob_start();
				lsx_business_template( $this->args['template'] );
				$this->html .= ob_get_clean();
				$this->loop_bottom();
			}
		}
	}

	/**
	 * Outputs the closing loop actions.
	 *
	 * @return void
	 */
	public function loop_bottom() {
		if ( false === $this->args['carousel'] && $this->args['columns'] === $this->counter ) {
			$this->html   .= wp_kses_post( '</div>' );
			$this->html   .= wp_kses_post( '<div class="row row-flex">' );
			$this->counter = 0;
		}
	}

	/**
	 * Ends the html output and resets the query.
	 */
	public function end_loop() {
		$this->html .= '</div></div>';
		$this->reset_query();
		$this->is_shortcode = false;
	}

	/**
	 *  Sets the query back to false
	 */
	public function reset_query() {
		$this->query = false;
		wp_reset_postdata();
	}

	/**
	 * Returns the boolean if it is the shortcode loop or not.
	 *
	 * @return boolean
	 */
	public function is_shortcode() {
		return $this->is_shortcode;
	}
}
