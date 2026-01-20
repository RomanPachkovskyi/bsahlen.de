<?php
namespace VamtamElementor\Widgets\Form;

use ElementorPro\Modules\Forms\Widgets\Form as Elementor_Form;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

// Extending the Form widget.

// Is Pro Widget.
if ( ! \VamtamElementorIntregration::is_elementor_pro_active() ) {
	return;
}

// Theme preferences.
if ( ! \Vamtam_Elementor_Utils::is_widget_mod_active( 'form' ) ) {
	return;
}

if ( vamtam_theme_supports( 'form--btn-hover-anim' ) ) {
	function add_button_style_section_controls( $controls_manager, $widget ) {
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
	}
	// Style - Buttons section
	function section_style_before_section_end( $widget, $args ) {
		$controls_manager = \Elementor\Plugin::instance()->controls_manager;
		add_button_style_section_controls( $controls_manager, $widget );
	}
	add_action( 'elementor/element/form/section_button_style/before_section_end', __NAMESPACE__ . '\section_style_before_section_end', 10, 2 );

	// Vamtam_Widget_Form.
	function widgets_registered() {
		class Vamtam_Widget_Form extends Elementor_Form {
			public $extra_depended_scripts = [
				'vamtam-form',
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
					'vamtam-form',
					VAMTAM_ELEMENTOR_INT_URL . '/assets/js/widgets/form/vamtam-form' . $suffix . '.js',
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
		$widgets_manager->unregister( 'form' );
		$widgets_manager->register( new Vamtam_Widget_Form );
	}
	add_action( \Vamtam_Elementor_Utils::get_widgets_registration_hook(), __NAMESPACE__ . '\widgets_registered', 100 );
}
