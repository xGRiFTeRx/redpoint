<?php
/*
Plugin Name: RED POINT Widgets
Plugin URI:  https://github.com/xGRiFTeRx/redpoint
Description: Custom Elementor widgets for the RED POINT store (RTL Hebrew). One widget per
             section of the Figma design, so a section can be fixed in isolation.
Version:     1.10.0
Author:      Rovic de Lara
Text Domain: redpoint-widgets
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'REDPOINT_WIDGETS_VERSION', '1.10.0' );
define( 'REDPOINT_WIDGETS_FILE', __FILE__ );
define( 'REDPOINT_WIDGETS_PATH', plugin_dir_path( __FILE__ ) );
define( 'REDPOINT_WIDGETS_URL', plugin_dir_url( __FILE__ ) );

/**
 * A panel category of our own, so the RED POINT sections are not lost among Elementor's
 * hundred stock widgets.
 */
add_action(
	'elementor/elements/categories_registered',
	function ( $elements_manager ) {
		$elements_manager->add_category(
			'redpoint',
			array(
				'title' => 'RED POINT',
				'icon'  => 'fa fa-circle',
			)
		);
	}
);

/**
 * Register the widgets. One `require_once` + one `register()` per section — add a line
 * here as each section lands.
 */
// Shared helper: the product card that four sections render.
require_once REDPOINT_WIDGETS_PATH . 'includes/product-card.php';

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-header-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-hero-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-trust-strip-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-promo-banner-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-category-grid-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-best-sellers-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-worth-attention-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-blog-teaser-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-testimonials-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-brand-story-widget.php';
		require_once REDPOINT_WIDGETS_PATH . 'widgets/class-footer-widget.php';

		$widgets_manager->register( new \RedPoint\Widgets\Header_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Hero_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Trust_Strip_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Promo_Banner_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Category_Grid_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Best_Sellers_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Worth_Attention_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Blog_Teaser_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Testimonials_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Brand_Story_Widget() );
		$widgets_manager->register( new \RedPoint\Widgets\Footer_Widget() );
	}
);

/**
 * Register the carousel script (paging for the product/blog/testimonial rows). Registered
 * here and declared via a widget's get_script_depends() so it only loads on pages that
 * actually use it.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_register_script(
			'redpoint-carousel',
			REDPOINT_WIDGETS_URL . 'assets/js/redpoint-carousel.js',
			array(),
			REDPOINT_WIDGETS_VERSION,
			true
		);
	}
);

/**
 * Front-end assets. One stylesheet per widget, plus a shared one — same as the widgets
 * themselves, so a section's styling can be found and fixed without reading a 5000-line
 * file.
 */
function redpoint_widgets_enqueue_assets() {
	wp_enqueue_style(
		'redpoint-widgets',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint.css',
		array(),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-header',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-header.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-hero',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-hero.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-promo-banner',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-promo-banner.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-category-grid',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-category-grid.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	// Shared product card, then the sections that use it.
	wp_enqueue_style(
		'redpoint-widgets-product-card',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-product-card.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	// Shared carousel/dots styling, used by Best Sellers and the Blog Teaser.
	wp_enqueue_style(
		'redpoint-widgets-carousel',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-carousel.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-best-sellers',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-best-sellers.css',
		array( 'redpoint-widgets-product-card', 'redpoint-widgets-carousel' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-worth-attention',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-worth-attention.css',
		array( 'redpoint-widgets-product-card' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-blog-teaser',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-blog-teaser.css',
		array( 'redpoint-widgets-carousel' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-testimonials',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-testimonials.css',
		array( 'redpoint-widgets-carousel' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-brand-story',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-brand-story.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-footer',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-footer.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);

	wp_enqueue_style(
		'redpoint-widgets-trust-strip',
		REDPOINT_WIDGETS_URL . 'assets/css/redpoint-trust-strip.css',
		array( 'redpoint-widgets' ),
		REDPOINT_WIDGETS_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'redpoint_widgets_enqueue_assets' );

/**
 * The same stylesheets inside the Elementor editor, or a section looks unstyled while it
 * is being edited and the client cannot tell what they are placing.
 */
add_action( 'elementor/editor/after_enqueue_styles', 'redpoint_widgets_enqueue_assets' );

/**
 * Inline an SVG from assets/icons.
 *
 * The design's icons are STROKE paths on a fill="none" root. Elementor's own icon control
 * forces `fill: <color>` onto the svg and every path inside it, which fills the outline in
 * and turns each icon into a solid blob — so the stock control is not usable for these.
 * Inlining the file keeps the stroke intact and lets CSS colour it through `currentColor`.
 *
 * @param string $name Icon filename without extension, e.g. 'trust-1'.
 * @return string Inline SVG markup, or '' if the file is missing.
 */
function redpoint_icon( $name ) {
	$name = preg_replace( '/[^a-z0-9\-]/i', '', $name );
	$file = REDPOINT_WIDGETS_PATH . 'assets/icons/' . $name . '.svg';

	if ( ! $name || ! file_exists( $file ) ) {
		return '';
	}

	// Not escaped: this is our own file from our own plugin directory, never user input —
	// the filename is sanitised above and a missing file returns empty.
	return file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions
}
