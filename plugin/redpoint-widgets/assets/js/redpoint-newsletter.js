/*
 * RED POINT — newsletter demo submit.
 *
 * When the form has no action URL (data-demo="1"), intercept submit, validate the email,
 * and swap the subtitle for the success message — the same behaviour as the Next.js
 * reference. With an action URL the form posts normally and this does nothing.
 */
( function () {
	'use strict';

	function init( form ) {
		if ( form.getAttribute( 'data-demo' ) !== '1' ) {
			return; // Real endpoint set — let the browser submit.
		}

		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			var input = form.querySelector( '.rp-news__input' );
			if ( ! input || ! input.value || input.value.indexOf( '@' ) === -1 ) {
				input && input.focus();
				return;
			}

			var panel = form.closest( '.rp-news__panel' );
			var sub   = panel && panel.querySelector( '.rp-news__subtitle' );
			var btn   = form.querySelector( '.rp-news__submit' );
			var msg   = form.getAttribute( 'data-success' ) || '';

			if ( sub ) {
				sub.textContent = msg;
			}
			if ( btn ) {
				btn.classList.add( 'is-done' );
				btn.textContent = '✓';
			}
			input.value = '';
		} );
	}

	function initAll() {
		var forms = document.querySelectorAll( '.rp-news__form' );
		for ( var i = 0; i < forms.length; i++ ) {
			init( forms[ i ] );
		}
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
} )();
