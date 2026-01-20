<?php

namespace VamtamElementor\Widgets\Icon;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'icon' ) ) {
	return;
}

function update_icon_color_controls( $controls_manager, $widget ) {
	$widget->start_injection( [
		'of' => 'hover_animation',
		'at' => 'after',
	] );

	$widget->add_control(
		'vamtam_use_parent_hover',
		[
			'label'        => __( 'Apply hover colors when hovering over parent', 'vamtam-elementor-integration' ),
			'description'  => __( 'Add the vamtam-icon-hover-parent class to the chosen parent element. This option only affects the color and background of the icon, not the animation.', 'vamtam-elementor-integration' ),
			'type'         => $controls_manager::SWITCHER,
			'prefix_class' => 'vamtam-has-',
			'return_value' => 'parent-hover',
			'default'      => '',
			'render_type'  => 'template',
		]
	);
	$widget->end_injection();

	\Vamtam_Elementor_Utils::add_control_options( $controls_manager, $widget, 'hover_primary_color', [
		'selectors' => [
			'{{WRAPPER}}' => '--vamtam-hpc: {{VALUE}};',
		],
	] );

	\Vamtam_Elementor_Utils::add_control_options( $controls_manager, $widget, 'hover_secondary_color', [
		'selectors' => [
			'{{WRAPPER}}' => '--vamtam-hsc: {{VALUE}};',
		],
	] );
}

function section_style_icon_before_section_end( $widget ) {
	$controls_manager = \Elementor\Plugin::instance()->controls_manager;
	update_icon_color_controls( $controls_manager, $widget );
}
add_action( 'elementor/element/icon/section_style_icon/before_section_end', __NAMESPACE__ . '\section_style_icon_before_section_end' );