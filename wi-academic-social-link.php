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

function wi_academic_social_link_block_init() {
	register_block_type( __DIR__ . '/build', array(
		'render_callback' => 'render_block_academic_social_link',
	) );
}
add_action( 'init', 'wi_academic_social_link_block_init' );

function wi_academic_social_link_block_register_styles() {
	// var_dump(wp_styles()->registered);

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

function wi_add_academic_social_link_to_social_links( $metadata ) {
	if ( $metadata['name'] === 'core/social-links' ) {
		if ( ! in_array( 'wielgosz-info/academic-social-link', $metadata['allowedBlocks'] ) ) {
			$metadata['allowedBlocks'][] = 'wielgosz-info/academic-social-link';
		}
	}

	return $metadata;
}
add_filter( 'block_type_metadata', 'wi_add_academic_social_link_to_social_links' );
