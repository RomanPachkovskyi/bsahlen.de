<?php
/**
 * Lasoon General Settings
 *
 * @package Lasoon\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Lasoon_Settings_About', false ) ) {
	return new Lasoon_Settings_About();
}
include_once dirname( __FILE__ ) . '/class-lasoon-setting-page.php';

/**
 * Lasoon_Admin_Settings_General.
 */
class Lasoon_Settings_About extends Lasoon_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'about';
		$this->label = esc_html__( 'About Us', 'lasoon' );

		parent::__construct();
	}

	/**
	 * Get settings or the default section.
	 *
	 * @return array
	 */
	protected function get_settings_for_default_section() {

		$settings =
		array(



			array(
				'title' => esc_html__( 'About Us', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__( 'This section is specially for About Us section.', 'lasoon' ),
				'id'    => 'about_page_settings',
			),

			array(
				'title'    => esc_html__( 'About Us Enable', 'lasoon' ),
				'desc'     => esc_html__( 'Enable the button to display About Us section.', 'lasoon' ),
				'id'       => 'lasoon_about_show',
				'default'  => 'Unable',
				'unable'   => esc_html__('Show', 'lasoon'),
				'disable'   => esc_html__('Hide', 'lasoon'),
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'About Section will be shown after enable it.', 'lasoon' ),
			),

			array(
				'title'             => esc_html__( 'About Title', 'lasoon' ),
				'desc'              => esc_html__( 'Set heading for about us.', 'lasoon' ),
				'id'                => 'lasoon_about_head',
				'default'           => esc_html__("About Us", 'lasoon'),
				'desc_tip'          => true,
				'type'              => 'text',
			),
			array(
				'title'             => esc_html__( 'About Description', 'lasoon' ),
				'desc'              => esc_html__( 'Set description for about us.', 'lasoon' ),
				'id'                => 'lasoon_about_desc',
				'default'           => esc_html__("Lacus interdum orci, varius posuere rhoncus amet. Ultrices arcu, et duis in purus.", 'lasoon'),
				'desc_tip'          => true,
				'rows'				=> 1,
				'type'              => 'texteditor',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'about_page_settings',
			),

			array(
				'title' => esc_html__( 'Get In Touch', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__('This section is specially for Get In Touch section.', 'lasoon'),
				'id'    => 'git_options_setting',
			),

			array(
				'title'    => esc_html__( 'Get in Touch Enable', 'lasoon' ),
				'desc'     => esc_html__( 'Enable the button to display Get In Touch section.', 'lasoon' ),
				'id'       => 'lasoon_git_show',
				'default'  => 'Unable',
				'unable'   => esc_html__('Show', 'lasoon'),
				'disable'   => esc_html__('Hide', 'lasoon'),
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'Get in Touch will be shown after enable it.', 'lasoon' ),
			),

			array(
				'title'             => esc_html__( 'Get In Touch Title', 'lasoon' ),
				'desc'              => esc_html__( 'Set get in touch for about us.', 'lasoon' ),
				'id'                => 'lasoon_git_head',
				'default'           => esc_html__("Get In Touch", 'lasoon'),
				'desc_tip'          => true,
				'type'              => 'text',
			),
			array(
				'title'             => esc_html__( 'Get In Touch Description', 'lasoon' ),
				'desc'              => esc_html__( 'Set description for get in touch.', 'lasoon' ),
				'id'                => 'lasoon_git_desc',
				'default'           => esc_html__("Lacus interdum orci, varius posuere rhoncus amet. Ultrices arcu, et duis in purus.", 'lasoon'),
				'desc_tip'          => true,
				'rows'				=> 1,
				'type'              => 'texteditor',
			),

			array(
				'title'             => esc_html__( 'Contact Number', 'lasoon' ),
				'desc'              => esc_html__( 'Set contact number for about us.', 'lasoon' ),
				'id'                => 'lasoon_git_contact',
				'default'           => esc_html__("1234657890", 'lasoon'),
				'desc_tip'          => true,
				'type'              => 'tel',
			),
			array(
				'title'             => esc_html__( 'Email Address', 'lasoon' ),
				'desc'              => esc_html__( 'Set email for about us.', 'lasoon' ),
				'id'                => 'lasoon_git_email',
				'default'           => esc_html__("abc@gmail.com", 'lasoon'),
				'desc_tip'          => true,
				'type'              => 'email',
			),
			array(
				'title'             => esc_html__( 'Address', 'lasoon' ),
				'desc'              => esc_html__( 'Set address for about us.', 'lasoon' ),
				'id'                => 'lasoon_git_address',
				'default'           => esc_html__("Get In Touch", 'lasoon'),
				'desc_tip'          => true,
				'rows'				=> 1,
				'type'              => 'texteditor',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'git_options_setting',
			),


			array(
				'title' => esc_html__( 'Contact Us', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__('This section is specially for Contact us section.', 'lasoon'),
				'id'    => 'contact_us_settings',
			),
			array(
				'title'    => esc_html__( 'Contact Us Enable', 'lasoon' ),
				'desc'     => esc_html__( 'Enable the button to display Contact Us section.', 'lasoon' ),
				'id'       => 'lasoon_contact_us',
				'default'  => 'Unable',
				'unable'   => esc_html__('Show', 'lasoon'),
				'disable'   => esc_html__('Hide', 'lasoon'),
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'Contact Us will be shown after enable it.', 'lasoon' ),
			),
			array(
				'type' => 'sectionend',
				'id'   => 'contact_us_settings',
			),

		);

return apply_filters( 'lasoon_general_settings', $settings );
}
}

return new Lasoon_Settings_About();