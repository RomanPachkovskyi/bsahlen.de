<?php
namespace VamtamElementor\Widgets\Container;

// Extending the Container widget.

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'container' ) ) {
	return;
}

if ( vamtam_theme_supports( 'container--vamtam-sticky-header-controls' ) ) {
	// Removes render attributes recursively.
	function unset_render_attrs_recursively( $element ) {
		// As long as we have children, keep removing their render attributes.
		if ( ! empty( $element->get_children() ) ) {
			foreach ($element->get_children() as $child ) {
				unset_render_attrs_recursively( $child );
			}
		}

		$attrs = $element->get_render_attributes();
		foreach ( $attrs as $key => $value ) {
			$element->remove_render_attribute( $key );
		}
	}

	function container_after_render( $element ) {
		if ( 'container' === $element->get_name() ) {
			// Editor.
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode()  ) {
				return;
			}

			$settings         = $element->get_settings_for_display();
			$is_sticky_header = isset( $settings['use_vamtam_sticky_header'] ) && '' !== $settings['use_vamtam_sticky_header'];

			if (  $is_sticky_header ) {
				// Make a copy of the element, to be used as a spacer for the sticky header.
				$container_html = '';

				ob_start();
				// Before container render.
				$element->before_render();

				// Container content.
				foreach ( $element->get_children() as $child ) {
					// Cause of the double render, we end up with some duplicate render attributes.
					// We remove the ones already printed by the previous render, so they don't get rendered twice.
					unset_render_attrs_recursively( $child );
					$child->print_element();
				}

				// After container render.
				$element->after_render();

				$container_html = ob_get_clean();

				// Add the spacer class.
				$replace      = 'vamtam-sticky-header';
				$replaceWith  = 'vamtam-sticky-header vamtam-sticky-header--spacer';
				// Regex needs to be only for exact match.
				$container_html = preg_replace('/(?<=\s|^)(?:' . $replace . ')(?=\s|$)/', $replaceWith, $container_html, 1);

				// Print spacer element.
				echo $container_html; //xss ok
			}
		}
	}
	add_action( 'elementor/frontend/after_render', __NAMESPACE__ . '\container_after_render', 10, 2 );

	function add_vamtam_sticky_header_controls( $controls_manager, $widget ) {
		$widget->start_injection( [
			'of' => 'sticky_effects_offset',
			'at' => 'after',
		] );
		$widget->add_control(
			'use_vamtam_sticky_header',
			[
				'label' => __( 'Theme Sticky Header (Desktop)', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => '',
				'return_value' => 'vamtam-sticky-header',
				'condition' => [
					'sticky' => '',
				]
			]
		);
		$widget->add_control(
			'vamtam_sticky_header_transparent',
			[
				'label' => __( 'Header Is Transparent', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => '',
				'return_value' => 'vamtam-sticky-header--transparent-header',
				'condition' => [
					'sticky' => '',
					'use_vamtam_sticky_header!' => '',
				],
			]
		);
		$widget->add_control(
			'vamtam_sticky_offset',
			[
				'label' => esc_html__( 'Offset (px)', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 500,
				'condition' => [
					'sticky' => '',
					'use_vamtam_sticky_header!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--vamtam-sticky-offset: {{VALUE}}px',
				]
			]
		);
		$widget->add_control(
			'vamtam_offset_on_sticky',
			[
				'label' => __( 'Offset on Sticky', 'vamtam-elementor-integration' ),
				'description' => __( 'Offset will be applied to the sticky state of the header as well. When disabled, offset is only applied on the initial position of the sticky header.', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SWITCHER,
				'prefix_class' => '',
				'return_value' => 'vamtam-sticky-header--offset-on-sticky',
				'condition' => [
					'sticky' => '',
					'use_vamtam_sticky_header!' => '',
				],
			]
		);
		$widget->end_injection();
	}

	// Advanced - Motion effects.
	function section_effects_before_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_vamtam_sticky_header_controls( $controls_manager, $widget );
	}
	add_action( 'elementor/element/container/section_effects/before_section_end', __NAMESPACE__ . '\section_effects_before_section_end', 10, 2 );
}

if ( vamtam_theme_supports( 'container--clip-path-section' ) ) {
	function add_clip_path_section( $controls_manager, $widget ) {
		$widget->start_controls_section(
			'section_vamtam_cp',
			[
				'label' => esc_html__( 'Clip Path', 'vamtam-elementor-integration' ),
				'tab' => $controls_manager::TAB_STYLE,
			]
		);

		// Use Clip Path
		$widget->add_control(
			'vamtam_use_cp',
			[
				'type'  => $controls_manager::SWITCHER,
				'label' => esc_html__('Use Clip Path', 'vamtam-elementor-integration'),
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'theme-cp',
			]
		);

		// Type
		$widget->add_control(
			'vamtam_cp',
			[
				'label' => esc_html__('Type', 'vamtam-elementor-integration'),
				'type' => $controls_manager::SELECT,
				'options' => [
					'' => esc_html__('None', 'vamtam-elementor-integration'),
					'top-left' => esc_html__('Top Left', 'vamtam-elementor-integration'),
					'top-right' => esc_html__('Top Right', 'vamtam-elementor-integration'),
					'top-center' => esc_html__('Top Center', 'vamtam-elementor-integration'),
					'bottom-left' => esc_html__('Bottom Left', 'vamtam-elementor-integration'),
					'bottom-right' => esc_html__('Bottom Right', 'vamtam-elementor-integration'),
					'bottom-center' => esc_html__('Bottom Center', 'vamtam-elementor-integration'),
				],
				'prefix_class' => 'vamtam-cp-',
				'condition' => [
					'vamtam_use_cp!' => '',
				],
			]
		);

		$widget->end_controls_section();
	}
	// Style - Shape Divider (After section end).
	function section_shape_divider_after_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_clip_path_section( $controls_manager, $widget );
	}
	add_action( 'elementor/element/container/section_shape_divider/after_section_end', __NAMESPACE__ . '\section_shape_divider_after_section_end', 10, 2 );
}
