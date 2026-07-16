/*
 * RED POINT — carousel paging.
 *
 * The design shows a row of cards with dots beneath (product rows, blog, testimonials).
 * The markup is rendered as pages (each a grid) with a dot per page; this shows one page at
 * a time and the dots switch between them. No dependency, no build step.
 *
 * Progressive enhancement: with JS off, the first page shows and the rest are simply the
 * following pages in the DOM — nothing breaks, you just do not get paging.
 */
( function () {
	'use strict';

	function initCarousel( root ) {
		var pages = root.querySelectorAll( '.rp-carousel__page' );
		var dots  = root.querySelectorAll( '.rp-carousel__dot' );
		if ( pages.length < 2 ) {
			return; // One page — nothing to page through.
		}

		function show( index ) {
			for ( var i = 0; i < pages.length; i++ ) {
				var on = i === index;
				pages[ i ].hidden = ! on;
				if ( dots[ i ] ) {
					dots[ i ].classList.toggle( 'is-active', on );
					dots[ i ].setAttribute( 'aria-selected', on ? 'true' : 'false' );
				}
			}
		}

		for ( var i = 0; i < dots.length; i++ ) {
			( function ( idx ) {
				dots[ idx ].addEventListener( 'click', function () {
					show( idx );
				} );
			} )( i );
		}

		show( 0 );
	}

	function initAll() {
		var roots = document.querySelectorAll( '.rp-carousel' );
		for ( var i = 0; i < roots.length; i++ ) {
			initCarousel( roots[ i ] );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}

	// Re-init inside the Elementor editor when a widget is (re)rendered.
	if ( window.elementorFrontend ) {
		window.jQuery( window ).on( 'elementor/frontend/init', function () {
			window.elementorFrontend.hooks.addAction( 'frontend/element_ready/redpoint_best_sellers.default', initAll );
		} );
	}
} )();
