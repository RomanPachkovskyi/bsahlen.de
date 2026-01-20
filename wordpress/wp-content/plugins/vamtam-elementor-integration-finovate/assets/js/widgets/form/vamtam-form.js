class VamtamForm extends elementorModules.frontend.handlers.Base {

	onInit( ...args ) {
		super.onInit( ...args );
        const _this = this;
        if ( document.readyState !== 'complete' ) {
			jQuery( window ).on( 'load', () => {
				_this.handleBtnHoverAnims();
			} );
		} else {
			setTimeout( () => {
				_this.handleBtnHoverAnims();
			}, 50 );
		}
	}

	handleBtnHoverAnims() {
		if ( ! this.$element.hasClass( 'vamtam-has-btn-hover-anim' ) ) {
			return;
		}

		// Submit Btn
		const $submitBtn = this.$element.find( 'button[type="submit"]' ),
			$submitBtnIcon = $submitBtn.length && $submitBtn.find( '.elementor-button-icon' ),
			$submitBtnTxt = $submitBtn.length && this.$element.find( 'button[type="submit"] .elementor-button-text' );

			if ( $submitBtnTxt.length ) {
				const $spanWrap = jQuery(' <span> ').addClass( 'vamtam-btn-text-wrap' );
				$spanWrap.appendTo( $submitBtnTxt.parent() );
				$submitBtnTxt.appendTo( $spanWrap );
				$submitBtnTxt.clone().appendTo( $spanWrap ).addClass( 'vamtam-btn-text-abs' );
				$submitBtnTxt.addClass( 'vamtam-btn-text' );
			}

		// Step Btns
		const $stepBtnTxtEls = this.$element.find( 'button[type="button"].elementor-button' );
		if ( $stepBtnTxtEls.length ) {
			$stepBtnTxtEls.each( ( index, btnTxtEl ) => {
				const $btnTxtEl = jQuery( btnTxtEl );
				const $spanParent = jQuery( '<span>' );
				const $spanWrap = jQuery(' <span> ').addClass( 'vamtam-btn-text-wrap' );
				$submitBtnIcon.length && $submitBtnIcon.clone().appendTo( $spanParent );
				$spanWrap.appendTo( $spanParent );
				const $btnInnerTxtEls = jQuery( `
                    <span class="elementor-button-text vamtam-btn-text">${$btnTxtEl.text()}</span>
                    <span class="elementor-button-text vamtam-btn-text-abs">${$btnTxtEl.text()}</span>
                ` );
                $btnInnerTxtEls.appendTo( $spanWrap );
                $btnTxtEl.text('');
				$spanParent.appendTo( $btnTxtEl );
			} );
		}
	}
}


jQuery( window ).on( 'elementor/frontend/init', () => {
	if ( !elementorFrontend.elementsHandler || !elementorFrontend.elementsHandler.attachHandler ) {
		const addHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamForm, {
				$element,
			} );
		};

		elementorFrontend.hooks.addAction( 'frontend/element_ready/form.default', addHandler, 100 );
	} else {
        elementorFrontend.elementsHandler.attachHandler( 'form', VamtamForm );
	}
} );
