<?php
namespace VamtamElementor\Widgets\Button;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'button' ) ) {
	return;
}

if ( vamtam_theme_supports( 'button--content-align-fix' ) ) {
	function update_content_align_control( $controls_manager, $widget ) {
		\Vamtam_Elementor_Utils::add_control_options( $controls_manager, $widget, 'content_align', [
			'prefix_class' => 'vamtam%s-content-align-',
		] );
	}
}

if ( vamtam_theme_supports( 'button--use-theme-icon-styles' ) ) {
	function add_button_icon_controls( $controls_manager, $widget ) {
		$ctrl_prefix = 'vamtam_';

		$ctrl_condition = [
			'selected_icon[value]!' => '',
			'vamtam_use_icon_styles!' => '',
		];

		// Icon Heading
		$widget->add_control(
			"{$ctrl_prefix}icon_heading",
			[
				'type' => $controls_manager::HEADING,
				'label' => esc_html__('Icon', 'vamtam-elementor-integration'),
				'condition' => $ctrl_condition,
			]
		);

		// Use Theme Icon Styles
		$widget->add_control(
			"{$ctrl_prefix}use_icon_styles",
			[
				'type'  => $controls_manager::SWITCHER,
				'label' => esc_html__('Use Theme Icon Styles', 'vamtam-elementor-integration'),
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'icon-styles',
				'default' => '',
				'render_type' => 'template',
				'condition' => [
					'selected_icon[value]!' => '',
				],
				'separator' => 'before'
			]
		);

		$base_selector         = '{{WRAPPER}}.vamtam-has-icon-styles .elementor-button-icon';
		$base_selector_inside  = '{{WRAPPER}}.vamtam-has-icon-styles:not(.vamtam-has-outside-icon) .elementor-button-icon';
		$base_selector_outside = '{{WRAPPER}}.vamtam-has-outside-icon .vamtam-btn-icon-wrap';

		$base_selector_hover         = '{{WRAPPER}}.vamtam-has-icon-styles .elementor-button:is(:hover, :focus) .elementor-button-icon';
		$base_selector_inside_hover  = '{{WRAPPER}}.vamtam-has-icon-styles:not(.vamtam-has-outside-icon) .elementor-button:is(:hover, :focus) .elementor-button-icon';
		$base_selector_outside_hover = '{{WRAPPER}}.vamtam-has-outside-icon .elementor-button:is(:hover, :focus) .vamtam-btn-icon-wrap';

		// Use Theme Animation
		$widget->add_control(
			'vamtam_use_hover_anim',
			[
				'type'         => $controls_manager::SWITCHER,
				'label'        => esc_html__('Use Theme Hover Animation', 'vamtam-elementor-integration'),
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'hover-anim',
				'default'      => '',
				'render_type'  => 'template',
				'condition'    => [
					'selected_icon[value]!' => '',
					'vamtam_use_icon_styles!' => '',
				],
			]
		);

		$widget->add_control(
			"{$ctrl_prefix}outside_icon",
			[
				'type'  => $controls_manager::SWITCHER,
				'label' => esc_html__('Icon Outside Button', 'vamtam-elementor-integration'),
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'outside-icon',
				'default' => '',
				'render_type' => 'template',
				'condition' => $ctrl_condition,
				'separator' => 'after'
			]
		);

		\Vamtam_Elementor_Utils::add_control_options( $controls_manager, $widget, 'icon_align', [
			'prefix_class' => 'vamtam-icon-pos-',
		] );

		\Vamtam_Elementor_Utils::add_control_options( $controls_manager, $widget, 'icon_indent', [
			'selectors' => [
				'{{WRAPPER}}' => '--vamtam-gap: {{SIZE}}{{UNIT}};',
			],
		] );

		// Icon Size
		$widget->add_control(
			"{$ctrl_prefix}icon_size",
			[
				'label' => __( 'Size', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'selectors' => [
					"{$base_selector} :is(svg, i)" => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}' => '--vamtam-icon-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => $ctrl_condition,
			]
		);

		$widget->start_controls_tabs( "{$ctrl_prefix}tabs_icon_style", [
			'condition' => $ctrl_condition,
		] );

		$widget->start_controls_tab(
			"{$ctrl_prefix}tab_icon_normal",
			[
				'label' => esc_html__( 'Normal', 'vamtam-elementor-integration' ),
				'condition' => $ctrl_condition,
			]
		);

		// Icon Color
		$widget->add_control(
			"{$ctrl_prefix}icon_color",
			[
				'label' => __( 'Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					"{$base_selector} :is(svg, i)" => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
				'condition' => $ctrl_condition,
			]
		);

		// Icon Bg Color
		$widget->add_control(
			"{$ctrl_prefix}icon_bg_color",
			[
				'label' => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					"{$base_selector_inside} :is(svg, i), {$base_selector_outside}" => 'background-color: {{VALUE}};',
				],
				'condition' => $ctrl_condition,
			]
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab(
			"{$ctrl_prefix}tab_icon_hover",
			[
				'label' => esc_html__( 'Hover', 'vamtam-elementor-integration' ),
				'condition' => $ctrl_condition,
			]
		);

		// Icon Hover Color
		$widget->add_control(
			"{$ctrl_prefix}icon_hover_color",
			[
				'label' => __( 'Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					"{$base_selector_hover} :is(svg, i)" => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
				'condition' => $ctrl_condition,
			]
		);

		// Icon Hover Bg Color
		$widget->add_control(
			"{$ctrl_prefix}icon_bg_hover_color",
			[
				'label' => __( 'Background Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'default' => '',
				'selectors' => [
					"{$base_selector_inside_hover} :is(svg, i),
					{$base_selector_outside_hover}" => 'background-color: {{VALUE}};',
				],
				'condition' => $ctrl_condition,
			]
		);

		// Icon Hover Border Color
		$widget->add_control(
			"{$ctrl_prefix}icon_hover_border_color",
			[
				'label' => esc_html__( 'Border Color', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::COLOR,
				'selectors' => [
					"{$base_selector_inside_hover} :is(svg, i),
					{$base_selector_outside_hover}" => 'border-color: {{VALUE}};',
				],
				'condition' => $ctrl_condition,
			]
		);

		// Transition Duration
		$widget->add_control(
			"{$ctrl_prefix}icon_hover_transition_duration",
			[
				'label' => esc_html__( 'Transition Duration', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'default' => [
					'unit' => 's',
				],
				'selectors' => [
					"{$base_selector} :is(svg, i), {$base_selector}, {$base_selector_outside}" => 'transition-duration: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		// Border
		$widget->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => "{$ctrl_prefix}icon_border",
				'selector' => "{$base_selector_inside} :is(svg, i), {$base_selector_outside}",
				'separator' => 'before',
				'condition' => $ctrl_condition,
				'selectors' => [
					'{{WRAPPER}}' => '--vamtam-icon-border-ttl: calc( {{LEFT}}{{UNIT}} + {{RIGHT}}{{UNIT}} );',
				],

			]
		);

		// Border Radius
		$widget->add_responsive_control(
			"{$ctrl_prefix}icon_border_radius",
			[
				'label' => esc_html__( 'Border Radius', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					"{$base_selector_inside} :is(svg, i), {$base_selector_outside}" => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => $ctrl_condition,
			]
		);

		// Margin
		$widget->add_responsive_control(
			"{$ctrl_prefix}icon_margin",
			[
				'label' => esc_html__( 'Margin', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					"{$base_selector_inside} :is(svg, i)" => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'selected_icon[value]!' => '',
					'vamtam_use_icon_styles!' => '',
					'vamtam_use_hover_anim' => '',
					'vamtam_outside_icon' => '',
				],
			]
		);

		// Padding
		$widget->add_responsive_control(
			"{$ctrl_prefix}icon_padding",
			[
				'label' => esc_html__( 'Padding', 'vamtam-elementor-integration' ),
				'type' => $controls_manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					"{$base_selector} :is(svg, i)" => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}' => '--vamtam-icon-padding-ttl: calc( {{LEFT}}{{UNIT}} + {{RIGHT}}{{UNIT}} );',
				],
				'separator' => 'after',
				'condition' => $ctrl_condition,
			]
		);

		// Use Theme Icon Styles
		$widget->add_control(
			"{$ctrl_prefix}use_icon_styles",
			[
				'type'  => $controls_manager::SWITCHER,
				'label' => esc_html__('Use Theme Icon Styles', 'vamtam-elementor-integration'),
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'icon-styles',
				'default' => '',
				'render_type' => 'template',
				'condition' => [
					'selected_icon[value]!' => '',
				],
				'separator' => 'before'
			]
		);
	}

	// Vamtam_Widget_Button.
	function widgets_registered() {
		class Vamtam_Widget_Button extends \Elementor\Widget_Button {
			public $extra_depended_scripts = [
				'vamtam-button',
			];

			// Extend constructor.
			public function __construct($data = [], $args = null) {
				parent::__construct($data, $args);

				$this->register_assets();

				$this->add_extra_script_depends();
			}

			// Register the assets the widget depends on.
			public function register_assets() {
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				wp_register_script(
					'vamtam-button',
					VAMTAM_ELEMENTOR_INT_URL . '/assets/js/widgets/button/vamtam-button' . $suffix . '.js',
					[
						'elementor-frontend'
					],
					\VamtamElementorIntregration::PLUGIN_VERSION,
					true
				);
			}

			// Assets the widget depends upon.
			public function add_extra_script_depends() {
				// Scripts
				foreach ( $this->extra_depended_scripts as $script ) {
					$this->add_script_depends( $script );
				}
			}
		}

		// Replace current divider widget with our extended version.
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
		$widgets_manager->unregister( 'button' );
		$widgets_manager->register( new Vamtam_Widget_Button );
	}
	add_action( \Vamtam_Elementor_Utils::get_widgets_registration_hook(), __NAMESPACE__ . '\widgets_registered', 100 );
}

if ( vamtam_theme_supports( [ 'button--content-align-fix', 'button--use-theme-icon-styles' ] ) ) {
	// Style - Button section
	function section_style_before_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		if ( vamtam_theme_supports( 'button--content-align-fix' ) ) {
			update_content_align_control( $controls_manager, $widget );
		}
		if ( vamtam_theme_supports( 'button--use-theme-icon-styles' ) ) {
			add_button_icon_controls( $controls_manager, $widget );
		}
	}
	add_action( 'elementor/element/button/section_style/before_section_end', __NAMESPACE__ . '\section_style_before_section_end', 10, 2 );
}
