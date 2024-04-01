<?php
/**
 * Server-side rendering of the `academic-social-link` blocks.
 * This is a copy of the `core/social-link` `index.php`
 * with the modification to only allow selected services.
 *
 * @package WI\AcademicSocialLink
 */


/**
 * Renders the `wiegosz-info/academic-social-link` block on server.
 *
 * @since 0.1.0
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    InnerBlocks content of the Block.
 * @param WP_Block $block      Block object.
 *
 * @return string Rendered HTML of the referenced block.
 */
function render_block_academic_social_link( $attributes, $content, $block ) {
	$open_in_new_tab = isset( $block->context['openInNewTab'] ) ? $block->context['openInNewTab'] : false;

	$service     = isset( $attributes['service'] ) ? $attributes['service'] : 'Icon';
	$url         = isset( $attributes['url'] ) ? $attributes['url'] : false;
	$label       = ! empty( $attributes['label'] ) ? $attributes['label'] : block_academic_social_link_get_name( $service );
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

	$icon               = block_academic_social_link_get_icon( $service );
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
 * @return string SVG Element for service icon.
 */
function block_academic_social_link_get_icon( $service ) {
	$services = block_academic_social_link_services();
	if ( isset( $services[ $service ] ) && isset( $services[ $service ]['icon'] ) ) {
		return $services[ $service ]['icon'];
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
function block_academic_social_link_get_name( $service ) {
	$services = block_academic_social_link_services();
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
function block_academic_social_link_services( ) {
	$services_data = array(
		'orcid-id' => array(
			'name' => 'ORCID iD',
			'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="m22 12c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10 10 4.477 10 10zm-11.492-3.82h3.25c3.094 0 4.453 2.211 4.453 4.187 0 2.149-1.68 4.188-4.438 4.188h-3.265zm-1.766 8.367h-1.203v-8.367h1.203zm2.969-1.078h1.914c2.727 0 3.352-2.071 3.352-3.102 0-1.68-1.071-3.101-3.414-3.101h-1.852zm-2.781-9.032c0 .43-.352.79-.789.79-.438 0-.789-.36-.789-.79 0-.437.351-.789.789-.789.437 0 .789.36.789.789z" fill-rule="evenodd"/></svg>',
		),
	);

	$services_data = apply_filters( 'block_academic_social_link_services', $services_data );

	return $services_data;
}
