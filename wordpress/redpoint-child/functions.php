<?php
/**
 * Red Point child theme.
 *
 * Deliberately thin. The layout is built in Elementor, not in PHP templates — this file
 * only does the things Elementor cannot do for itself:
 *
 *   1. Load the parent (Hello Elementor) stylesheet, then ours.
 *   2. Put Futurism and Google Sans into Elementor's font picker, so a widget can just
 *      select them instead of needing a Custom Fonts licence or hand-written CSS.
 *   3. Preload the two fonts that paint above the fold, so headings don't flash.
 *
 * @package redpoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Called directly — bail.
}

/**
 * Load parent then child stylesheet.
 *
 * The child is enqueued with the parent as a dependency, so our design tokens and
 * @font-face rules always land AFTER Hello Elementor's reset and win on equal specificity.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'hello-elementor',
			get_template_directory_uri() . '/style.css',
			array(),
			wp_get_theme( 'hello-elementor' )->get( 'Version' )
		);

		wp_enqueue_style(
			'redpoint',
			get_stylesheet_directory_uri() . '/style.css',
			array( 'hello-elementor' ),
			wp_get_theme()->get( 'Version' )
		);
	},
	20
);

/**
 * Register the two families with Elementor.
 *
 * Elementor's font dropdown is grouped (Google / System / Custom). Adding a group and
 * listing the families against it makes them selectable in every widget's Typography
 * control. Elementor will NOT emit @font-face for these — it assumes the theme does,
 * which style.css does.
 *
 * This is the free-tier route. Elementor Pro's "Custom Fonts" screen does the same thing
 * through the UI; if the site ends up on Pro, either approach works — but do not do both,
 * or the face gets declared twice.
 */
add_filter(
	'elementor/fonts/groups',
	function ( $groups ) {
		$groups['redpoint'] = esc_html__( 'Red Point', 'redpoint' );
		return $groups;
	}
);

add_filter(
	'elementor/fonts/additional_fonts',
	function ( $fonts ) {
		$fonts['Futurism']    = 'redpoint';
		$fonts['Google Sans'] = 'redpoint';
		return $fonts;
	}
);

/**
 * Preload the fonts that paint above the fold.
 *
 * The hero headline is Futurism and the nav is Google Sans, so both are needed for the
 * first paint. Without a preload the browser only discovers them after the stylesheet
 * parses, and the headline visibly swaps a beat late.
 *
 * Only the Hebrew subset of Google Sans is preloaded — the site is Hebrew, and preloading
 * the Latin files would waste bandwidth on fonts the first screen never uses.
 */
add_action(
	'wp_head',
	function () {
		$fonts = array(
			'fonts/Futurism-Light.woff2',
			'fonts/Futurism-Regular.woff2',
			'fonts/GoogleSans-400-hebrew.woff2',
			'fonts/GoogleSans-500-hebrew.woff2',
		);

		foreach ( $fonts as $font ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( get_stylesheet_directory_uri() . '/' . $font )
			);
		}
	},
	1
);

/**
 * Allow SVG uploads — ADMINISTRATORS ONLY.
 *
 * The design's icons are SVGs (trust badges, value badges, carets, arrows), and Elementor's
 * icon widget can point at an uploaded SVG. WordPress blocks the mime type by default.
 *
 * The capability check is the whole point: an SVG is an XML document and can carry inline
 * <script>, so letting an untrusted role upload one is a stored-XSS vector. Restricting to
 * `unfiltered_html` means only admins — who can already inject scripts by design — gain
 * anything. Do NOT loosen this to `upload_files`, which subscribers and customers hold on
 * a WooCommerce site.
 */
add_filter(
	'upload_mimes',
	function ( $mimes ) {
		if ( current_user_can( 'unfiltered_html' ) ) {
			$mimes['svg'] = 'image/svg+xml';
		}
		return $mimes;
	}
);

/**
 * WordPress sniffs file contents and rejects an SVG whose real type does not match the
 * extension. Trust the extension only for the roles allowed above.
 */
add_filter(
	'wp_check_filetype_and_ext',
	function ( $data, $file, $filename ) {
		if ( ! current_user_can( 'unfiltered_html' ) ) {
			return $data;
		}
		if ( str_ends_with( strtolower( $filename ), '.svg' ) ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		return $data;
	},
	10,
	3
);

/**
 * WooCommerce support.
 *
 * Hello Elementor already declares this, but the child theme must re-declare it or the
 * parent's support is not inherited for the gallery features.
 */
add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
);
