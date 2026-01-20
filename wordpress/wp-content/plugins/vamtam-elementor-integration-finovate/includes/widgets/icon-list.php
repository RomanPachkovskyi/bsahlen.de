<?php
namespace VamtamElementor\Widgets\IconList;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'icon-list' ) ) {
	return;
}

if ( vamtam_theme_supports( 'icon-list--use-icon-theme-styles' ) ) {
	function icon_style_controls( $controls_manager, $widget ) {
		$widget->start_injection( [
			'of' => 'icon_color',
			'at' => 'after',
		] );

		$widget->add_control(
			'vamtam_icon_bg_color',
			[
				'label' => __( 'Icon Bg Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon' => 'background: {{VALUE}};',
				],
				'prefix_class'                => 'vamtam-icon-bg-',
				'vamtam_selectors_dictionary' => [
					''              => 'no',
					'{{ANY_VALUE}}' => 'yes',
				],
			]
		);

		$widget->end_injection();

		$widget->start_injection( [
			'of' => 'icon_color_hover',
			'at' => 'after',
		] );

		$widget->add_control(
			'vamtam_icon_bg_color_hover',
			[
				'label' => __( 'Icon Bg Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon' => 'background: {{VALUE}};',
				],
				'prefix_class'                => 'vamtam-icon-bg-',
				'vamtam_selectors_dictionary' => [
					''              => 'no',
					'{{ANY_VALUE}}' => 'yes',
				],
			]
		);

		$widget->end_injection();
	}

	function section_icon_style_before_section_end( $widget ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		icon_style_controls( $controls_manager, $widget );
	}
	add_action( 'elementor/element/icon-list/section_icon_style/before_section_end', __NAMESPACE__ . '\section_icon_style_before_section_end' );
}
