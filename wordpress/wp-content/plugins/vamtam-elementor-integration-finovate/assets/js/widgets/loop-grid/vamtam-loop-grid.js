// Added on Loop Grid (classic/vamtam_classic skin)
class VamtamLoopGrid extends elementorModules.frontend.handlers.Base {

	onInit() {
		elementorModules.frontend.handlers.Base.prototype.onInit.apply( this, arguments );
		this.bindEvents();

		this.handleBtnHoverAnim();
	}

	handleBtnHoverAnim() {
		if ( ! this.$element.hasClass( 'vamtam-has-btn-hover-anim' ) ) {
			return;
		}

		const $btnTxtEl = this.$element.find( '.e-loop__load-more .elementor-button-text' );
		if ( $btnTxtEl.length ) {
			const $spanWrap = jQuery(' <span> ').addClass( 'vamtam-btn-text-wrap' );
			$spanWrap.appendTo( $btnTxtEl.parent() )
			$btnTxtEl.appendTo( $spanWrap );
			$btnTxtEl.clone().appendTo( $spanWrap ).addClass( 'vamtam-btn-text-abs' );
			$btnTxtEl.addClass( 'vamtam-btn-text' );
		}
	}

}

jQuery( window ).on( 'elementor/frontend/init', () => {
	if ( !elementorFrontend.elementsHandler || !elementorFrontend.elementsHandler.attachHandler ) {

		const vamtamMasonryHandler = ( $element ) => {
			elementorFrontend.elementsHandler.addHandler( VamtamLoopGrid, {
				$element,
			} );
		};

		elementorFrontend.hooks.addAction( 'frontend/element_ready/loop-grid.post', vamtamMasonryHandler, 100 );
	} else {
		elementorFrontend.elementsHandler.attachHandler( 'loop-grid', VamtamLoopGrid, 'post' );
	}
} );
