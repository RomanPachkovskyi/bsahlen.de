<?php
namespace VamtamElementor\Widgets\LoopGrid;

use \ElementorPro\Modules\LoopBuilder\Widgets\Loop_Grid as Elementor_Loop_Grid;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Is Pro Widget.
if ( ! \VamtamElementorIntregration::is_elementor_pro_active() ) {
	return;
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'loop-grid' ) ) {
	return;
}

if ( vamtam_theme_supports( 'loop-grid--load-more-btn-hover-anim' ) ) {

	function add_use_btn_hover_anim_control( $controls_manager, $widget ) {
		$widget->start_injection( [
			'of' => 'heading_load_more_style_button',
			'at' => 'after',
		] );
		// Use Theme Animation
		$widget->add_control(
			'vamtam_use_btn_hover_anim',
			[
				'type'  => $controls_manager::SWITCHER,
				'label' => esc_html__('Use Theme Hover Animation', 'vamtam-elementor-integration'),
				'prefix_class' => 'vamtam-has-',
				'return_value' => 'btn-hover-anim',
				'default' => '',
				'render_type' => 'template',
			]
		);
		$widget->end_injection();
	}
	// Style - Button section
	function section_style_before_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_use_btn_hover_anim_control( $controls_manager, $widget );
	}
	add_action( 'elementor/element/loop-grid/section_style/before_section_end', __NAMESPACE__ . '\section_style_before_section_end', 10, 2 );

	// Vamtam_Widget_Loop_Grid.
	function widgets_registered() {

		// Is Pro Widget.
		if ( ! \VamtamElementorIntregration::is_elementor_pro_active() ) {
			return;
		}

		if ( ! class_exists( '\ElementorPro\Modules\LoopBuilder\Widgets\Loop_Grid' ) ) {
			return; // Elementor's autoloader acts weird sometimes.
		}

		class Vamtam_Widget_Loop_Grid extends Elementor_Loop_Grid {

			public function get_script_depends() {
				return [
					'imagesloaded',
					'vamtam-loop-grid',
				];
			}

			// Extend constructor.
			public function __construct($data = [], $args = null) {
				parent::__construct($data, $args);

				$this->register_assets();
			}

			/*
				Skins (and their controls) are already registered in the parent class.

				Registering them again (by calling parent::__construct()), would trigger the re-addition of their options, which have already
				been registered at this point, leading to $control_stack issues (adding exisitng control options).
			*/
			protected function register_skins() {}

			// Register the assets the widget depends on.
			public function register_assets() {
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				wp_register_script(
					'vamtam-loop-grid',
					VAMTAM_ELEMENTOR_INT_URL . '/assets/js/widgets/loop-grid/vamtam-loop-grid' . $suffix . '.js',
					[
						'elementor-frontend'
					],
					\VamtamElementorIntregration::PLUGIN_VERSION,
					true
				);
			}
		}

		// Replace current tabs widget with our extended version.
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;
		$widgets_manager->unregister( 'loop-grid' );
		$widgets_manager->register( new Vamtam_Widget_Loop_Grid );
	}
	add_action( \Vamtam_Elementor_Utils::get_widgets_registration_hook(), __NAMESPACE__ . '\widgets_registered', 100 );
}
