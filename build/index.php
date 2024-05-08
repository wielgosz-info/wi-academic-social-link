<?php
/**
 * Server-side rendering of the `academic-social-link` blocks.
 * This is a copy of the `core/social-link` `index.php`
 * with the modification to only allow selected services.
 *
 * @package WI\AcademicSocialLink
 */


/**
 * Renders the `wielgosz-info/wi-academic-social-link` block on server.
 *
 * @since 0.1.0
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    InnerBlocks content of the Block.
 * @param WP_Block $block      Block object.
 *
 * @return string Rendered HTML of the referenced block.
 */
function wi_academic_social_link_render_block( $attributes, $content, $block ) {
	$open_in_new_tab = isset( $block->context['openInNewTab'] ) ? $block->context['openInNewTab'] : false;

	$service     = isset( $attributes['service'] ) ? $attributes['service'] : 'Icon';
	$url         = isset( $attributes['url'] ) ? $attributes['url'] : false;
	$label       = ! empty( $attributes['label'] ) ? $attributes['label'] : wi_academic_social_link_get_name( $service );
	$rel         = isset( $attributes['rel'] ) ? $attributes['rel'] : '';
	$show_labels = array_key_exists( 'showLabels', $block->context ) ? $block->context['showLabels'] : false;

	// Don't render a link if there is no URL set.
	if ( ! $url ) {
		return '';
	}

	/**
	 * Prepend emails with `mailto:` if not set.
	 * The `is_email` returns false for emails with schema.
	 */
	if ( is_email( $url ) ) {
		$url = 'mailto:' . antispambot( $url );
	}

	/**
	 * Prepend URL with https:// if it doesn't appear to contain a scheme
	 * and it's not a relative link starting with //.
	 */
	if ( ! parse_url( $url, PHP_URL_SCHEME ) && ! str_starts_with( $url, '//' ) ) {
		$url = 'https://' . $url;
	}

	$icon               = wi_academic_social_link_get_icon( $service, $block->context );
	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => 'wp-social-link wp-block-social-link wp-social-link-' . $service . block_core_social_link_get_color_classes( $block->context ),
			'style' => block_core_social_link_get_color_styles( $block->context ),
		)
	);

	$link  = '<li ' . $wrapper_attributes . '>';
	$link .= '<a href="' . esc_url( $url ) . '" class="wp-block-social-link-anchor">';
	$link .= $icon;
	$link .= '<span class="wp-block-social-link-label' . ( $show_labels ? '' : ' screen-reader-text' ) . '">' . esc_html( $label ) . '</span>';
	$link .= '</a></li>';

	$processor = new WP_HTML_Tag_Processor( $link );
	$processor->next_tag( 'a' );
	if ( $open_in_new_tab ) {
		$processor->set_attribute( 'rel', trim( $rel . ' noopener nofollow' ) );
		$processor->set_attribute( 'target', '_blank' );
	} elseif ( '' !== $rel ) {
		$processor->set_attribute( 'rel', trim( $rel ) );
	}
	return $processor->get_updated_html();
}

/**
 * Returns the SVG for social link.
 *
 * @since 0.1.0
 *
 * @param string $service The service icon.
 *
 * @return string DIV Element for service icon.
 */
function wi_academic_social_link_get_icon( $service, $context ) {
	$services = wi_academic_social_link_services();
	if ( isset( $services[ $service ] ) && isset( $services[ $service ]['icon'] ) ) {
		$color       = isset( $context['iconColor'] ) ? $context['iconColor'] : null;
		$color_value = isset( $context['iconColorValue'] ) ? $context['iconColorValue'] : null;

		if ( isset( $services[ $service ]['icon'][ $color ] ) ) {
			return $services[ $service ]['icon'][ $color ];
		} elseif ( isset( $services[ $service ]['icon'][ $color_value ] ) ) {
			return $services[ $service ]['icon'][ $color_value ];
		} else {
			return $services[ $service ]['icon']['original'];
		}
	}

	return $services['share']['icon'];
}

/**
 * Returns the brand name for social link.
 *
 * @since 0.1.0
 *
 * @param string $service The service icon.
 *
 * @return string Brand label.
 */
function wi_academic_social_link_get_name( $service ) {
	$services = wi_academic_social_link_services();
	if ( isset( $services[ $service ] ) && isset( $services[ $service ]['name'] ) ) {
		return $services[ $service ]['name'];
	}

	return $services['share']['name'];
}

/**
 * Returns the SVG for social link.
 *
 * @since 0.1.0
 *
 * @return array
 */
function wi_academic_social_link_services() {
	$services_data = array(
		'orcid-id'      => array(
			'name' => 'ORCID iD',
			'icon' => array(
				'original'                        => file_get_contents( plugin_dir_path( __FILE__ ) . '../src/icons/ORCIDiD_iconvector.svg' ),
				'var(--wp--preset--color--black)' => file_get_contents( plugin_dir_path( __FILE__ ) . '../src/icons/ORCIDiD_iconbwvector.svg' ),
				'var(--wp--preset--color--white)' => file_get_contents( plugin_dir_path( __FILE__ ) . '../src/icons/ORCID-iD_icon_reversed_vector.svg' ),
			),
		),
		'arxiv-profile' => array(
			'name' => 'arXiv Profile',
			'icon' => array(
				'original'                        => file_get_contents( plugin_dir_path( __FILE__ ) . '../src/icons/arxiv-logomark-small.svg' ),
				'var(--wp--preset--color--black)' => file_get_contents( plugin_dir_path( __FILE__ ) . '../src/icons/arxiv-logomark-small-black.svg' ),
				'var(--wp--preset--color--white)' => file_get_contents( plugin_dir_path( __FILE__ ) . '../src/icons/arxiv-logomark-small-white.svg' ),
			),
		),
	);

	$services_data = apply_filters( 'wi_academic_social_link_services', $services_data );

	return $services_data;
}
