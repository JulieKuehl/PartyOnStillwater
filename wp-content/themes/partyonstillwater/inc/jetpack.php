<?php
/**
 * Jetpack Compatibility File
 * See: https://jetpack.me/
 *
 * @package PartyOnStillwater
 */

/**
 * Add theme support for Infinite Scroll.
 * See: https://jetpack.me/support/infinite-scroll/
 */
function partyonstillwater_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'partyonstillwater_infinite_scroll_render',
		'footer'    => 'page',
	) );
} // end function partyonstillwater_jetpack_setup
add_action( 'after_setup_theme', 'partyonstillwater_jetpack_setup' );

function partyonstillwater_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/content', get_post_format() );
	}
} // end function partyonstillwater_infinite_scroll_render