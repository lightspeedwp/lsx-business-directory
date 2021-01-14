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
	if ( lsx_bd_is_preview() ) {
		$listing_id = get_query_var( 'preview-listing' );
		$image      = wp_get_attachment_image_src( get_post_thumbnail_id( $listing_id ), $size );
		$image_src  = ( strpos( $image[0], 'cover-logo.png' ) === false ) ? $image[0] : $image_src;
		if ( isset( $_FILES['lsx_bd__thumbnail_id_upload'] ) && isset( $_FILES['lsx_bd__thumbnail_id_upload']['tmp_name'] ) && '' !== $_FILES['lsx_bd__thumbnail_id_upload']['tmp_name'] ) {
			$image     = getimagesize( $_FILES['lsx_bd__thumbnail_id_upload']['tmp_name'] ); // @codingStandardsIgnoreLine
			$image_src = 'data:' . $image['mime'] . ';base64,' . base64_encode( file_get_contents( $_FILES['lsx_bd__thumbnail_id_upload']['tmp_name'] ) ); // @codingStandardsIgnoreLine
		}
	} elseif ( has_post_thumbnail( $id ) ) {
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
		'echo'    => true,
		'title'   => esc_html__( 'You might also be interested in...', 'lsx-member-directory' ),
		'excerpt' => false,
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
		'excerpt'    => $args['excerpt'],
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
		'echo'    => true,
		'title'   => esc_html__( 'Recent Listings', 'lsx-member-directory' ),
		'excerpt' => false,
	);
	$args     = wp_parse_args( $args, $defaults );
	$lsx_bd   = lsx_business_directory();
	$params   = array(
		'title_text' => $args['title'],
		'carousel'   => true,
		'orderby'    => 'recent',
		'custom_css' => 'lsx-bd-recent-listings',
		'excerpt'    => $args['excerpt'],
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
		'echo'    => true,
		'title'   => esc_html__( 'Featured Listings', 'lsx-member-directory' ),
		'excerpt' => false,
	);
	$args     = wp_parse_args( $args, $defaults );
	$lsx_bd   = lsx_business_directory();
	$params   = array(
		'title_text' => $args['title'],
		'carousel'   => true,
		'orderby'    => 'featured',
		'custom_css' => 'lsx-bd-featured-listings',
		'excerpt'    => $args['excerpt'],
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
		'echo'    => true,
		'title'   => esc_html__( 'Listings', 'lsx-member-directory' ),
		'excerpt' => false,
	);
	$args     = wp_parse_args( $args, $defaults );
	$lsx_bd   = lsx_business_directory();
	$params   = array(
		'title_text' => $args['title'],
		'carousel'   => true,
		'orderby'    => 'rand',
		'custom_css' => 'lsx-bd-random-listings',
		'excerpt'    => $args['excerpt'],
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
		'columns'        => -1,
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
	if ( lsx_bd_is_preview() ) {
		$title = '';
		$saved = filter_input( INPUT_POST, 'lsx_bd_post_title' );
		if ( ! empty( $saved ) && '' !== $saved ) {
			$title = apply_filters( 'lsx_bd_single_business_title', '<h1 class="entry-title">' . $saved . '</h1>' );
		}
	} else {
		$title = apply_filters( 'lsx_bd_single_business_title', '<h1 class="entry-title">' . get_the_title() . '</h1>' );
	}
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
function lsx_bd_single_listing_meta( $echo = true ) {
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
		$entry_meta = ob_get_clean();
		$entry_meta = apply_filters( 'lsx_bd_single_listing_meta', $entry_meta );
	}
	if ( true === $echo ) {
		echo wp_kses_post( $entry_meta );
	} else {
		return $entry_meta;
	}
}

/**
 * This function outputs the single business listing title wrapped in <h1>.
 *
 * @param boolean $echo Output or return the title.
 * @return string
 */
function lsx_bd_listing_content( $echo = true ) {
	if ( lsx_bd_is_preview() ) {
		$content = '';
		$saved   = filter_input( INPUT_POST, 'lsx_bd_post_content' );
		if ( ! empty( $saved ) && '' !== $saved ) {
			$content = apply_filters( 'the_content', $saved );
		}
	} else {
		$content = get_the_content();
	}

	if ( true === $echo ) {
		echo wp_kses_post( $content );
	} else {
		return $content;
	}
}

/**
 * Outputs the excerpt for the archive listing.
 *
 * @param  string $before
 * @param  string $after
 * @param  boolean $echo
 * @param  boolean $force_excerpt
 * @return string
 */
function lsx_bd_archive_listing_excerpt( $before = '', $after = '', $echo = true, $force_excerpt = false ) {
	$key = 'archive';
	if ( is_search() ) {
		$key = 'engine';
	} elseif ( lsx_bd_is_shortcode() ) {
		$key = 'shortcode';
	}
	$is_enabled = lsx_bd_get_option( $key . '_excerpt_enable', false );
	if ( false !== $is_enabled && false === $force_excerpt ) {
		$excerpt = get_the_excerpt();
		ob_start();
		echo wp_kses_post( $excerpt );
		$excerpt = ob_get_clean();
		$excerpt = apply_filters( 'lsx_bd_' . $key . '_listing_excerpt', $before . $excerpt . $after );
		if ( true === $echo ) {
			echo wp_kses_post( $excerpt );
		} else {
			return $excerpt;
		}
	}
}

/**
 * Output the location and the industry for the archive listing.
 *
 * @param  string $before
 * @param  string $after
 * @param  boolean $echo
 * @param  string $colum_class
 * @return string
 */
function lsx_bd_archive_listing_meta( $before = '', $after = '', $echo = true, $colum_class = '' ) {
	$entry_meta = '';
	$industries = get_the_term_list( get_the_ID(), 'industry', '', ', ', '' );
	$locations  = get_the_term_list( get_the_ID(), 'location', '', ', ', '' );
	if ( ! empty( $industries ) || ! empty( $locations ) ) {
		ob_start();
		if ( ! empty( $industries ) ) {
			?>
			<div class="industry <?php echo esc_attr( $colum_class ); ?>">
				<span>
					<i class="fa fa-th"></i>
					<strong><?php esc_html_e( 'Industry', 'lsx-business-directory' ); ?>: </strong>
					<?php echo wp_kses_post( $industries ); ?>
				</span>
			</div>
			<?php
		}
		if ( ! empty( $locations ) ) {
			?>
			<div class="location <?php echo esc_attr( $colum_class ); ?>">
				<span>
					<i class="fa fa-globe"></i>
					<strong><?php esc_html_e( 'Location', 'lsx-business-directory' ); ?>: </strong>
					<?php echo wp_kses_post( $locations ); ?>
				</span>
			</div>
			<?php
		}
		$entry_meta = ob_get_clean();
		$entry_meta = apply_filters( 'lsx_bd_archive_listing_meta', $before . $entry_meta . $after );
	}
	if ( true === $echo ) {
		echo wp_kses_post( $entry_meta );
	} else {
		return $entry_meta;
	}
}

/**
 * Output the primary phone and primary email address stored on the listing.
 *
 * @param  boolean $echo
 * @return string
 */
function lsx_bd_archive_listing_contact_info( $before = '', $after = '', $echo = true, $colum_class = '' ) {
	$contact_info  = '';
	$primary_phone = get_post_meta( get_the_ID(), 'lsx_bd_primary_phone', true );
	$primary_email = get_post_meta( get_the_ID(), 'lsx_bd_primary_email', true );
	if ( ! empty( $primary_phone ) || ! empty( $primary_email ) ) {
		ob_start();
		if ( false !== $primary_phone && '' !== $primary_phone ) {
			?>
			<div class="telephone <?php echo esc_attr( $colum_class ); ?>">
				<span><i class="fa fa-phone-square"></i> <strong><?php esc_html_e( 'Phone', 'lsx-business-directory' ); ?>: </strong> <a href="tel:<?php echo esc_attr( str_replace( ' ', '', $primary_phone ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $primary_phone ); ?></a></span>
			</div>
			<?php
		}
		if ( false !== $primary_email && '' !== $primary_email ) {
			?>
			<div class="email <?php echo esc_attr( $colum_class ); ?>">
				<span><i class="fa fa-envelope-square"></i> <strong><?php esc_html_e( 'Email', 'lsx-business-directory' ); ?>: </strong> <a href="mailto:<?php echo esc_attr( $primary_email ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_attr( $primary_email ); ?></a></span>
			</div>
			<?php
		}
		$contact_info = ob_get_clean();
		$contact_info = apply_filters( 'lsx_bd_archive_listing_contact_info', $before . $contact_info . $after );
	}
	if ( true === $echo ) {
		echo wp_kses_post( $contact_info );
	} else {
		return $contact_info;
	}
}
/**
 * Undocumented function
 *
 * @param boolean $echo
 * @return void | string
 */
function lsx_bd_listing_map( $before = '', $after = '', $echo = true ) {
	return '';

	$map                  = '';
	$lsx_bd               = lsx_business_directory();
	if ( false === lsx_bd_get_option( 'google_maps_api_key', false ) ) {
		return;
	}
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
 * Outputs the Add new Listing button
 *
 * @param boolean $echo
 * @return void | string
 */
function lsx_bd_add_listing_button() {
	?>
		<a class="btn btn-secondary" href="<?php echo esc_attr( get_permalink( wc_get_page_id( 'myaccount' ) ) . lsx_bd_get_option( 'translations_listings_add_endpoint', 'add-listing' ) ); ?>/"><?php echo esc_attr__( 'Add new listing', 'lsx-business-directory' ); ?> <i class="fa fa-plus-circle"></i></a>
	<?php
}

/**
 * Outputs the Edit Listing button
 *
 * @param boolean $echo
 * @return void | string
 */
function lsx_bd_edit_listing_button() {
	?>
		<a href="<?php echo esc_attr( get_permalink( wc_get_page_id( 'myaccount' ) ) . lsx_bd_get_option( 'translations_listings_edit_endpoint', 'edit-listing' ) ); ?>/<?php the_ID(); ?>/" class="btn tertiary-border-btn"><?php echo esc_attr__( 'Edit', 'lsx-business-directory' ); ?></a>
	<?php
}

/**
 * Outputs the View Listing button
 *
 * @param boolean $echo
 * @return void | string
 */
function lsx_bd_view_listing_button() {
	?>
		<a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php echo esc_attr__( 'View', 'lsx-business-directory' ); ?></a>
	<?php
}

/**
 * Undocumented function
 *
 * @param string $before
 * @param string $after
 * @param boolean $echo
 * @return string
 */
function lsx_bd_subscription_details( $before = '', $after = '', $echo = true ) {
	$html = '';
	if ( 'on' === lsx_bd_get_option( 'woocommerce_enable_checkout', false ) && function_exists( 'wcs_get_subscription' ) ) {
		// if the current subscription product is the same as the subscription, then its the active one, and you dont need to redirect to the cart.
		$current_subscription = get_post_meta( get_the_ID(), '_lsx_bd_order_id', true );
		if ( false !== $current_subscription ) {
			$subscription = wcs_get_subscription( $current_subscription );
			if ( ! empty( $subscription ) ) {
				$label = sprintf(
					/* translators: %s: The subscription info */
					__( '<a href="%1$s">#%2$s - %3$s</a>', 'lsx-business-directory' ),
					$subscription->get_view_order_url(),
					$current_subscription,
					$subscription->get_status()
				);
				$html = $before . $label . $after;
			}
		}
	}
	if ( true === $echo ) {
		echo wp_kses_post( $html );
	} else {
		return $html;
	}
}
