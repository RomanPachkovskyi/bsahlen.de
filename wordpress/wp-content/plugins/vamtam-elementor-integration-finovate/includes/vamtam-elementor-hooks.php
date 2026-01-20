<?php
namespace VamtamElementor\ElementorHooks;

// Elementor actions.
add_action( 'elementor/editor/before_enqueue_scripts',   __NAMESPACE__ . '\enqueue_editor_scripts' );
add_action( 'elementor/frontend/before_enqueue_scripts', __NAMESPACE__ . '\frontend_before_enqueue_scripts' );
add_action( 'elementor/init', __NAMESPACE__ . '\elementor_init' );
add_action( 'elementor/element/after_add_attributes', __NAMESPACE__ . '\prefix_class' );

// Elementor filters
add_filter( 'elementor/image_size/get_attachment_image_html', __NAMESPACE__ . '\vamtam_add_no_lazy_class_to_img_element', 11, 4 );

function elementor_init() {
	// Theme-dependant.
	set_experiments_default_state();

	set_performance_options();
}

/*
	Sets all Stable features to their default value & disables all Ongoing experiments by default.
	Happens only once (based on option).
*/
function set_experiments_default_state() {
	if ( get_option( 'vamtam-set-experiments-default-state', false ) ) {
		return;
	}

	$exps     = \Elementor\Plugin::$instance->experiments;
	$features = $exps->get_features();

	// Features to force-disable.
	$fdisable = [
		'additional_custom_breakpoints',
		'e_font_icon_svg',
	];

	// Features needed for theme (order matters).
	$fenable = [
		'container',
		'nested-elements',
		'mega-menu',
	];

	foreach ( $features as $fname => $fdata ) {
		if ( $fdata['release_status'] === 'stable' ) {
			// Stable experiments.

			if ( in_array( $fname, $fdisable ) ) {
				// Force-disable.
				update_option( 'elementor_experiment-' . $fname, $exps::STATE_INACTIVE );
				continue;
			}

			// Force default state.
			update_option( 'elementor_experiment-' . $fname, $exps::STATE_DEFAULT );

		} else {
			// Ongoing experiments.

			if ( in_array( $fname, $fenable ) ) {
				continue;
			} else {
				// Force-disable.
				update_option( 'elementor_experiment-' . $fname, $exps::STATE_INACTIVE );
				// Set it's current default state to inactive
				$exps->set_feature_default_state( $fname, $exps::STATE_INACTIVE );
			}
		}
	}

	foreach ( $fenable as $fname ) {
		// Force-enable.
		update_option( 'elementor_experiment-' . $fname, $exps::STATE_ACTIVE );
		// Set it's current default state to active
		$exps->set_feature_default_state( $fname, $exps::STATE_ACTIVE );
	}

	update_option( 'vamtam-set-experiments-default-state', true );
}

function __return_inactive_experiment( $state ) {
	return \Elementor\Plugin::$instance->experiments::STATE_INACTIVE;
}

function set_performance_options() {
	add_filter( 'pre_option_elementor_element_cache_ttl', function( $ttl ) {
		return 'disable';
	}, 999 );

	$fdisable = [
		'e_element_cache',
	];

	// temporarily allow this only on internal sites
	if ( ! function_exists( 'vamtam_internal_adminbar' ) ) {
		$fdisable[] = 'e_optimized_markup';
	}

	$selectors = [];

	// Features to force-disable.
	foreach ( $fdisable as $fname ) {
		update_option( 'elementor_experiment-' . $fname, \Elementor\Plugin::$instance->experiments::STATE_INACTIVE );
		add_filter( 'pre_option_elementor_experiment-' . $fname, __NAMESPACE__ . '\__return_inactive_experiment' );
		$selectors[] = '.elementor_experiment-' . $fname;
	}

	add_action( 'admin_enqueue_scripts', function() use ( $selectors ) {
		$screen = get_current_screen();
		if ( $screen && $screen->id === 'elementor_page_elementor-settings' ) {
			$css = implode( ', ', $selectors ) . ' {
					display: none;
				}
			';
			wp_add_inline_style( 'elementor-admin', $css );
		}
	} );
}

function frontend_before_enqueue_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Enqueue JS for Elementor (frontend).
	wp_enqueue_script(
		'vamtam-elementor-frontend',
		VAMTAM_ELEMENTOR_INT_URL . 'assets/js/vamtam-elementor-frontend' . $suffix . '.js',
		[
			'elementor-frontend', // dependency
			'elementor-dialog', // dependency
		],
		\VamtamElementorIntregration::PLUGIN_VERSION,
		true //in footer
	);
}

function enqueue_editor_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	// Enqueue JS for Elementor editor.
	wp_enqueue_script(
		'vamtam-elementor',
		VAMTAM_ELEMENTOR_INT_URL . 'assets/js/vamtam-elementor' . $suffix . '.js',
		[],
		\VamtamElementorIntregration::PLUGIN_VERSION,
		true //in footer
	);
}

/*
	Add the no-lazy class to the <img> element, if the root widget element has it.
	We need this cause Elementor adds the Custom CSS classes to the root widget element
	but caching plugins need it directly on the <img> element in order not to lazy-load it.
*/
function vamtam_add_no_lazy_class_to_img_element( $html, $settings, $image_size_key, $image_key ) {
	if ( ! isset( $settings[ '_css_classes' ] ) || empty( $settings[ '_css_classes' ] ) || strpos( $settings[ '_css_classes' ], 'no-lazy' ) === false ) {
		return $html;
	}

	$attrClass = strpos( $html, "class=" );
	if ( $attrClass ) {
		$html = preg_replace( '/class="(.*)"/', 'class="' . 'no-lazy' . ' \1"', $html );
	} else {
		$html = preg_replace( '/src="(.*)"/', 'class="' . 'no-lazy' . '" src="\1"', $html );
	}

	return $html;
}

// like prefix_class, but runs them through vamtam_selectors_dictionary which supports {{ANY_VALUE}}
function prefix_class( $widget ) {
	$settings = $widget->get_settings_for_display();
	$controls = $widget->get_controls();

	$class_settings    = [];
	$original_settings = [];

	foreach ( $settings as $setting_key => $setting ) {
		if ( isset( $controls[ $setting_key ]['prefix_class'] ) ) {
			$value = $setting;

			if ( isset( $controls[ $setting_key ]['vamtam_selectors_dictionary'] ) ) {
				$value = $controls[ $setting_key ]['vamtam_selectors_dictionary'][ $value ] ?? ( $controls[ $setting_key ]['vamtam_selectors_dictionary']['{{ANY_VALUE}}'] ?? null );
			}

			if ( $value !== null ) {
				$class_settings[ $setting_key ]    = $value;
				$original_settings[ $setting_key ] = $setting; // as set by elementor

				if ( isset( $controls[ $setting_key ]['classes_dictionary'][ $setting ] ) ) {
					$original_settings[ $setting_key ] = $controls[ $setting_key ]['classes_dictionary'][ $setting ];
				}
			}
		}
	}

	foreach ( $class_settings as $setting_key => $setting ) {
		if ( empty( $setting ) && '0' !== $setting ) {
			continue;
		}

		$widget->remove_render_attribute( '_wrapper', 'class', $controls[ $setting_key ]['prefix_class'] . $original_settings[ $setting_key ] );
		$widget->add_render_attribute( '_wrapper', 'class', $controls[ $setting_key ]['prefix_class'] . $setting );
	}
}
