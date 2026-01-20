<?php
namespace VamtamElementor\Widgets\NestedAccordion;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'nested-accordion' ) ) {
	return;
}

if ( vamtam_theme_supports( 'nested-accordion--use-icon-theme-styles' ) ) {
	function add_use_theme_styles_control( $controls_manager, $widget ) {
		$widget->start_injection( [
			'of' => 'accordion_item_title_icon_active',
		] );
		// Use Theme Styles
		$widget->add_control(
			'vamtam_use_icon_theme_styles',
			[
				'label' => __( 'Use Theme Styles', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'icon-theme-styles',
				'default' => 'icon-theme-styles',
			]
		);
		// Icon Bg Color
		$widget->add_control(
			'vamtam_icon_bg_color',
			[
				'label' => __( 'Icon Bg Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					"{{WRAPPER}} .e-n-accordion-item-title-icon" => '--vamtam-n-accordion-icon-bg-color: {{VALUE}};',
				],
				'condition' => [
					'vamtam_use_icon_theme_styles!' => '',
				],
			]
		);
		$widget->end_injection();
	}

	function section_accordion_items_before_section_end( $widget ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_use_theme_styles_control( $controls_manager, $widget );
	}
	add_action( 'elementor/element/nested-accordion/section_items/before_section_end', __NAMESPACE__ . '\section_accordion_items_before_section_end' );
}
