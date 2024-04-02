<?php
/**
 * Plugin Name:       Academic Social Link
 * Description:       A copy of Gutenberg's Social Link block enabling academic icons.
 * Requires at least: 6.3
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Urszula Wielgosz and the WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wi-academic-social-link
 *
 * @package           WI\AcademicSocialLink
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/build/index.php';

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function wi_academic_social_link_block_init() {
	register_block_type( __DIR__ . '/build', array(
		'render_callback' => 'wi_academic_social_link_render_block',
	) );
}
add_action( 'init', 'wi_academic_social_link_block_init' );

/**
 * Registers the block's styles manually to ensure they are enqueued after the social links block styles.
 */
function wi_academic_social_link_block_register_styles() {
	wp_register_style(
		'wielgosz-info-academic-social-link',
		plugins_url( 'build/style-index.css', __FILE__ ),
		array(
			'wp-block-social-links'
		),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/style-index.css' )
	);
}
add_action( 'enqueue_block_assets', 'wi_academic_social_link_block_register_styles' );

/**
 * Adds the Academic Social Link block to the list of allowed blocks in the Social Links block.
 *
 * @param array $metadata The block type metadata.
 * @return array The block type metadata.
 */
function wi_add_academic_social_link_to_social_links( $metadata ) {
	if ( $metadata['name'] === 'core/social-links' ) {
		if ( ! in_array( 'wielgosz-info/wi-academic-social-link', $metadata['allowedBlocks'] ) ) {
			$metadata['allowedBlocks'][] = 'wielgosz-info/wi-academic-social-link';
		}
	}

	return $metadata;
}
add_filter( 'block_type_metadata', 'wi_add_academic_social_link_to_social_links' );
