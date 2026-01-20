class VamtamButton extends elementorModules.frontend.handlers.Base {

	onInit( ...args ) {
		super.onInit( ...args );
		this.handleBtnHoverAnim();
	}

	handleBtnHoverAnim() {
		if ( ! this.$element.hasClass( 'vamtam-has-hover-anim' ) ) {
			return;
		}

		const $btnIconEl = this.$element.find( '.elementor-button-icon' );
		if ( $btnIconEl.length ) {
			const clone = $btnIconEl.clone().addClass( 'vamtam-btn-icon-abs' );
			const $spanWrap = jQuery(' <span> ').addClass( 'vamtam-btn-icon-wrap' );

			$btnIconEl.addClass( 'vamtam-btn-icon' );

			$spanWrap.insertAfter( $btnIconEl )
			$btnIconEl.appendTo( $spanWrap );
			clone.appendTo( $spanWrap );
		}
	}
}


jQuery( window ).on( 'elementor/frontend/init', () => {
	if ( !elementorFrontend.elementsHandler || !elementorFrontend.elementsHandler.attachHandler ) {
		const addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamButton, {
				$element,
			} );
		};

		elementorFrontend.hooks.addAction( 'frontend/element_ready/button.default', addHandler, 100 );
	} else {
		elementorFrontend.elementsHandler.attachHandler( 'button', VamtamButton );
	}
} );
