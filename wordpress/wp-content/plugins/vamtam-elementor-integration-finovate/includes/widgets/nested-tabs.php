<?php
namespace VamtamElementor\Widgets\NestedTabs;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( vamtam_theme_supports( 'nested-tabs--use-theme-style' ) ) {
	function add_use_theme_style_controls( $controls_manager, $widget ) {
		// Use Theme Style.
		$widget->add_control(
			'vamtam_use_theme_style',
			[
				'label' => __( 'Use Theme Style', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'theme-style',
				'condition' => [
					'tabs_direction' => 'block-end',
				],
			]
		);

		// Label Headings.
		$widget->add_control(
			'vamtam_headings_label',
			[
				'label' => __('Headings', 'vamtam-elementor-integration'),
				'type' => $controls_manager::HEADING,
				'condition' => [
					'vamtam_use_theme_style!' => '',
				],
			]
		);

		// Headings Max Width.
		$widget->add_responsive_control(
			'vamtam_headings_max_width',
			[
				'label' => __('Max Width', 'vamtam-elementor-integration'),
				'type' => $controls_manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--vamtam-headings-max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'vamtam_use_theme_style!' => '',
				],
			]
		);

		// Headings Vertical Spacing.
		$widget->add_responsive_control(
			'vamtam_headings_vertical_spacing',
			[
				'label' => __( 'Vertical Spacing', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--vamtam-headings-vertical-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'vamtam_use_theme_style!' => '',
				],
			]
		);

		// Headings Horizontal Spacing.
		$widget->add_responsive_control(
			'vamtam_headings_horizontal_spacing',
			[
				'label' => __('Horizontal Spacing', 'vamtam-elementor-integration'),
				'type' => $controls_manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--vamtam-headings-horizontal-spacing: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'vamtam_use_theme_style!' => '',
				],
			]
		);
	}

	// Content - Tabs section.
	add_action( 'elementor/element/nested-tabs/section_tabs/before_section_end', function( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_use_theme_style_controls( $controls_manager, $widget );
	}, 10, 2 );
}

