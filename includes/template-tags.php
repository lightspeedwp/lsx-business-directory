<?php
/**
 * LSX Business Directory template functions.
 *
 * @package lsx-business-directory
 */

/**
 * Retrieves post thumbnail and averts werid unexpected behaviour.
 * Could re-wrap here if needed.
 *
 * @package     lsx-business-directory
 * @subpackage  template-tags
 * @category    single
 *
 * @param       string $id
 * @param       string $size int
 * @return      string
 */
function lsx_bd_get_thumbnail_wrapped( $id, $size = 'lsx-thumbnail-wide', $key = '' ) {
	$image_src = \lsx\business_directory\includes\get_placeholder( $size );
	if ( has_post_thumbnail( $id ) ) {
		$image     = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $size );
		$image_src = ( strpos( $image[0], 'cover-logo.png' ) === false ) ? $image[0] : $image_src;
	}
	return $image_src;
}

/**
 * Generates a comma seperated string from specified taxonomy.
 *
 * @package     lsx-business-directory
 * @subpackage  template-tags
 * @category    single
 *
 * @param       $id int
 * @param       $tax String
 */
function lsx_bd_get_formatted_taxonomy_str( $id, $tax, $link = false ) {
	$terms     = wp_get_post_terms( $id, $tax );
	$terms_str = $link ? array() : '';

	if ( ! empty( $terms ) ) {
		foreach ( $terms as $term ) {
			if ( $link ) {
				$terms_str[] = array(
					'slug' => trim( $term->slug ),
					'name' => trim( $term->name ),
				);
			} else {
				$terms_str .= $term->name . ', ';
			}
		}

		if ( ! $link ) {
			$terms_str = substr( $terms_str, 0, strlen( $terms_str ) - 2 );
		}
	}

	if ( 'post_format' == $tax && '' == $terms_str ) {
		$terms_str = 'Standard';
	}

	return $terms_str;
}

/**
 * Helper function that loads a particular template.
 *
 * @param   String $filename_base  Name of a template to load.
 *
 * @return  void
 */
function lsx_business_template( $filename_base ) {
	if ( file_exists( get_stylesheet_directory() . '/templates/' . $filename_base . '.php' ) ) {
		include get_stylesheet_directory() . '/templates/' . $filename_base . '.php';
	} elseif ( file_exists( LSX_BD_PATH . 'templates/' . $filename_base . '.php' ) ) {
		include LSX_BD_PATH . 'templates/' . $filename_base . '.php';
	}
}

/**
 * Returns the related listing block
 *
 * @param  boolean $echo
 * @return string
 */
function lsx_bd_related_listings( $args = array() ) {
	$defaults      = array(
		'echo'  => true,
		'title' => esc_html__( 'You might also be interested in...', 'lsx-member-directory' ),
	);
	$args          = wp_parse_args( $args, $defaults );
	$lsx_bd        = lsx_business_directory();
	$terms         = wp_get_post_terms( get_the_ID(), 'industry' );
	$prepped_terms = array();
	foreach ( $terms as $term ) {
		array_push( $prepped_terms, $term->term_id );
	}
	$params = array(
		'title_text' => $args['title'],
		'carousel'   => true,
		'taxonomy'   => 'industry',
		'terms'      => $prepped_terms,
		'orderby'    => 'rand',
		'custom_css' => 'lsx-bd-related-listings',
	);
	if ( true === $args['echo'] ) {
		echo wp_kses_post( $lsx_bd->frontend->widget->render( $params ) );
	} else {
		return $lsx_bd->frontend->widget->render( $params );
	}
}

/**
 * Returns the recent listing block
 *
 * @param  boolean $echo
 * @return string
 */
function lsx_bd_recent_listings( $args = array() ) {
	$defaults = array(
		'echo'  => true,
		'title' => esc_html__( 'Recent Listings', 'lsx-member-directory' ),
	);
	$args     = wp_parse_args( $args, $defaults );
	$lsx_bd   = lsx_business_directory();
	$params   = array(
		'title_text' => $args['title'],
		'carousel'   => true,
		'orderby'    => 'recent',
		'custom_css' => 'lsx-bd-recent-listings',
	);
	if ( true === $args['echo'] ) {
		echo wp_kses_post( $lsx_bd->frontend->widget->render( $params ) );
	} else {
		return $lsx_bd->frontend->widget->render( $params );
	}
}

/**
 * Returns the recent listing block
 *
 * @param  boolean $echo
 * @return string
 */
function lsx_bd_featured_listings( $args = array() ) {
	$defaults = array(
		'echo'  => true,
		'title' => esc_html__( 'Featured Listings', 'lsx-member-directory' ),
	);
	$args     = wp_parse_args( $args, $defaults );
	$lsx_bd   = lsx_business_directory();
	$params   = array(
		'title_text' => $args['title'],
		'carousel'   => true,
		'orderby'    => 'featured',
		'custom_css' => 'lsx-bd-featured-listings',
	);
	if ( true === $args['echo'] ) {
		echo wp_kses_post( $lsx_bd->frontend->widget->render( $params ) );
	} else {
		return $lsx_bd->frontend->widget->render( $params );
	}
}

/**
 * Returns the recent listing block
 *
 * @param  boolean $echo
 * @return string
 */
function lsx_bd_random_listings( $args = array() ) {
	$defaults = array(
		'echo'  => true,
		'title' => esc_html__( 'Listings', 'lsx-member-directory' ),
	);
	$args     = wp_parse_args( $args, $defaults );
	$lsx_bd   = lsx_business_directory();
	$params   = array(
		'title_text' => $args['title'],
		'carousel'   => true,
		'orderby'    => 'rand',
		'custom_css' => 'lsx-bd-random-listings',
	);
	if ( true === $args['echo'] ) {
		echo wp_kses_post( $lsx_bd->frontend->widget->render( $params ) );
	} else {
		return $lsx_bd->frontend->widget->render( $params );
	}
}

/**
 * Returns the industries nav block.
 *
 * @param  boolean $echo
 * @return string
 */
function lsx_bd_industries_nav( $args = array() ) {
	$defaults = array(
		'echo'           => true,
		'title_text'     => esc_html__( 'Pick an industry', 'lsx-member-directory' ),
		'carousel'       => false,
		'orderby'        => 'rand',
		'taxonomy'       => 'industry',
		'content_type'   => 'term',
		'template'       => 'single-industry-nav',
		'columns'        => 6,
		'custom_css'     => 'lsx-bd-industries-nav',
		'posts_per_page' => false,
	);
	$args     = wp_parse_args( $args, $defaults );
	$lsx_bd   = lsx_business_directory();
	if ( true === $args['echo'] ) {
		echo wp_kses_post( $lsx_bd->frontend->widget->render( $args ) );
	} else {
		return $lsx_bd->frontend->widget->render( $args );
	}
}

/**
 * Loads Business Template for Archive page for list layout.
 *
 * @return  void
 */
function lsx_business_row() {
	lsx_business_template( 'single-row-business' );
}

/**
 * Loads Business Template for Archive page for grid layout.
 *
 * @return  void
 */
function lsx_business_col() {
	lsx_business_template( 'single-col-business' );
}

/**
 * This function outputs the single business listing title wrapped in <h1>.
 *
 * @param boolean $echo Output or return the title.
 * @return string
 */
function lsx_bd_listing_title( $echo = true ) {
	$title = apply_filters( 'lsx_bd_single_business_title', '<h1 class="entry-title">' . get_the_title() . '</h1>' );
	if ( true === $echo ) {
		echo wp_kses_post( $title );
	} else {
		return $title;
	}
}

/**
 * Output the location and the industry for the single listing.
 *
 * @param  boolean $echo
 * @return string
 */
function lsx_bd_listing_meta( $echo = true ) {
	$entry_meta = '';
	$industries = get_the_term_list( get_the_ID(), 'industry', '', ', ', '' );
	$locations  = get_the_term_list( get_the_ID(), 'location', '', ', ', '' );
	if ( ! empty( $industries ) || ! empty( $locations ) ) {
		$col_class = '6';
		if ( empty( $industries ) || empty( $locations ) ) {
			$col_class = '12';
		}
		ob_start();
		?>
		<div class="entry-meta lsx-flex-row">
			<?php
			if ( ! empty( $industries ) ) {
				?>
				<div class="industry col-xs-12 col-sm-12 col-md-<?php echo esc_attr( $col_class ); ?>">
					<span>
						<i class="fa fa-th"></i>
						<strong><?php esc_html_e( 'Industry', 'lsx-business-directory' ); ?>: </strong>
						<?php echo wp_kses_post( $industries ); ?>
					</span>
				</div>
				<?php
			}
			?>

			<?php
			if ( ! empty( $locations ) ) {
				?>
				<div class="location col-xs-12 col-sm-12 col-md-<?php echo esc_attr( $col_class ); ?>">
					<span>
						<i class="fa fa-globe"></i>
						<strong><?php esc_html_e( 'Location', 'lsx-business-directory' ); ?>: </strong>
						<?php echo wp_kses_post( $locations ); ?>
					</span>
				</div>
				<?php
			}
			?>
		</div>
		<?php
		$entry_meta = apply_filters( 'lsx_bd_single_listing_meta', $entry_meta );
		$entry_meta = ob_get_clean();
	}
	if ( true === $echo ) {
		echo wp_kses_post( $entry_meta );
	} else {
		return $entry_meta;
	}
}

/**
 * Undocumented function
 *
 * @param boolean $echo
 * @return void | string
 */
function lsx_bd_listing_map( $before = '', $after = '', $echo = true ) {
	$map                  = '';
	$lsx_bd               = lsx_business_directory();
	$prefix               = 'lsx_bd';
	$address              = array();
	$business_address_1   = get_post_meta( get_the_ID(), $prefix . '_address_street_number', true );
	$business_address_2   = get_post_meta( get_the_ID(), $prefix . '_address_street_name', true );
	$business_address_3   = get_post_meta( get_the_ID(), $prefix . '_address_suburb', true );
	$business_address_4   = get_post_meta( get_the_ID(), $prefix . '_address_city', true );
	$business_postal_code = get_post_meta( get_the_ID(), $prefix . '_address_postal_code', true );
	$business_province    = get_post_meta( get_the_ID(), $prefix . '_address_province', true );
	$business_country     = get_post_meta( get_the_ID(), $prefix . '_address_country', true );
	if ( $business_address_1 ) {
		$address[] = $business_address_1;
	}
	if ( $business_address_2 ) {
		$address[] = $business_address_2;
	}
	if ( $business_address_3 ) {
		$address[] = $business_address_3;
	}
	if ( $business_address_4 ) {
		$address[] = $business_address_4;
	}
	if ( $business_postal_code ) {
		$address[] = $business_postal_code;
	}
	if ( $business_province ) {
		$address[] = $business_province;
	}
	if ( $business_country ) {
		$address[] = $business_country;
	}

	if ( ! empty( $address ) ) {
		$map = $lsx_bd->frontend->google_maps->render( implode( ',', $address ) );
		if ( '' !== $map ) {
			$map = $before . $map . $after;
		}
	}

	if ( true === $echo ) {
		echo wp_kses_post( $map );
	} else {
		return $map;
	}
}

/**
 * This gets the term thunbnail from the taxonomies.
 *
 * @param string $term_id
 * @param string $size
 * @param boolean $echo
 * @return string
 */
function lsx_bd_get_term_thumbnail( $term_id = '', $size = 'lsx-thumbnail-wide', $echo = false ) {
	$image = '';
	if ( '' !== $term_id ) {
		$image_src = get_term_meta( $term_id, 'lsx_bd_thumbnail_id', true );
		if ( false === $image_src || '' === $image_src ) {
			$image_src = \lsx\business_directory\includes\get_placeholder( $size, 'archive_thumbnail' );
			$image     = '<img width="150" height="150" src="' . $image_src . '" class="attachment-thumbnail size-thumbnail" alt="">';
		} elseif ( false !== $image_src && '' !== $image_src ) {
			$image = wp_get_attachment_image( $image_src, $size );
		}
		if ( false !== $echo ) {
			echo wp_kses_post( $image );
		}
	}
	return $image;
}
