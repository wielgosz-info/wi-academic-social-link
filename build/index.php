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
	if ( ! wp_parse_url( $url, PHP_URL_SCHEME ) && ! str_starts_with( $url, '//' ) ) {
		$url = 'https://' . $url;
	}

	$icon               = wi_academic_social_link_get_icon( $service, $block->context );
	$wrapper_attributes = get_block_wrapper_attributes(
		array(
			'class' => 'wp-social-link wp-block-social-link wp-social-link-' . $service . block_core_social_link_get_color_classes( $block->context ),
			'style' => block_core_social_link_get_color_styles( $block->context ),
		)
	);

	$link = '<li ' . $wrapper_attributes . '>';
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
 * @param array  $context The block context.
 *
 * @return string DIV Element for service icon.
 */
function wi_academic_social_link_get_icon( $service, $context ) {
	$services = wi_academic_social_link_services();
	if ( isset( $services[ $service ] ) && isset( $services[ $service ]['icon'] ) ) {
		$color       = isset( $context['iconColor'] ) ? $context['iconColor'] : null;
		$color_value = isset( $context['iconColorValue'] ) ? $context['iconColorValue'] : null;

		$icon = null;
		if ( isset( $services[ $service ]['icon'][ $color ] ) ) {
			$icon = $services[ $service ]['icon'][ $color ];
		} elseif ( isset( $services[ $service ]['icon'][ $color_value ] ) ) {
			$icon = $services[ $service ]['icon'][ $color_value ];
		} else {
			$icon = $services[ $service ]['icon']['original'];
		}

		if ( $icon ) {
			return sprintf( '<div class="wp-block-academic-social-link__icon">
					<img
						class="wp-block-academic-social-link__icon-img"
						src="%s"
						alt=""
					/>
				</div>', $icon );
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
				'original'                        => plugin_dir_url( __DIR__ ) . 'assets/ORCIDiD_iconvector.svg',
				'var(--wp--preset--color--black)' => plugin_dir_url( __DIR__ ) . 'assets/ORCIDiD_iconbwvector.svg',
				'var(--wp--preset--color--white)' => plugin_dir_url( __DIR__ ) . 'assets/ORCID-iD_icon_reversed_vector.svg',
				'black'                           => plugin_dir_url( __DIR__ ) . 'assets/ORCIDiD_iconbwvector.svg',
				'white'                           => plugin_dir_url( __DIR__ ) . 'assets/ORCID-iD_icon_reversed_vector.svg',
			),
		),
		'arxiv-profile' => array(
			'name' => 'arXiv Profile',
			'icon' => array(
				'original'                        => plugin_dir_url( __DIR__ ) . 'assets/arxiv-logomark-small.svg',
				'var(--wp--preset--color--black)' => plugin_dir_url( __DIR__ ) . 'assets/arxiv-logomark-small-black.svg',
				'var(--wp--preset--color--white)' => plugin_dir_url( __DIR__ ) . 'assets/arxiv-logomark-small-white.svg',
				'black'                           => plugin_dir_url( __DIR__ ) . 'assets/arxiv-logomark-small-black.svg',
				'white'                           => plugin_dir_url( __DIR__ ) . 'assets/arxiv-logomark-small-white.svg',
			),
		),
	);

	$services_data = apply_filters( 'wi_academic_social_link_services', $services_data );

	return $services_data;
}
