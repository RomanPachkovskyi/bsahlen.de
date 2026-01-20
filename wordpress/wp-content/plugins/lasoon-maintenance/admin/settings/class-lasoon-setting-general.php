<?php
/**
 * Lasoon General Settings
 *
 * @package Lasoon\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Lasoon_Settings_General', false ) ) {
	return new Lasoon_Settings_General();
}
include_once dirname( __FILE__ ) . '/class-lasoon-setting-page.php';

/**
 * Lasoon_Admin_Settings_General.
 */
class Lasoon_Settings_General extends Lasoon_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = esc_html__( 'General', 'lasoon' );

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
				'title' => esc_html__( 'General', 'lasoon' ),
				'type'  => 'title',
				'desc'  => wp_kses_post( '', 'lasoon' ),
				'id'    => 'blog_page_settings',
				'desc_tip' => false,
			),

			array(
				'title'    => esc_html__( 'Sidebar', 'lasoon' ),
				'desc'     => esc_html__( 'Enable Sidebar for display about us', 'lasoon' ),
				'id'       => 'lasoon_sidebar',
				'default'  => 'Unable',
				'class'    => 'switch_box',
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'About Us will be shown after enable it.', 'lasoon' ),
			),

			array(
				'id' => 'lasoon_logo',
				'type' => 'file',
				'title' => esc_html__('Logo', 'lasoon'),
				'desc' => esc_html__('','lasoon'),
				'field_desc' => esc_html__('Select <strong> Main Logo </strong>.', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),

			array(
				'title'     => esc_html__( 'Heading Title', 'lasoon' ),
				'desc'      => esc_html__( 'Set heading for your coming soon page.', 'lasoon' ),
				'id'        => 'lasoon_heading_text',
				'default'   => esc_html__("We're Coming Soon", 'lasoon'),
				'desc_tip'  => true,
				'type'      => 'text',
			),

			array(
				'title'    => esc_html__( 'Heading Subtitle Enable', 'lasoon' ),
				'desc'     => esc_html__( '', 'lasoon' ),
				'id'       => 'lasoon_subtitle_enable',
				'default'  => esc_html__('Unable', 'lasoon'),
				'unable'   => esc_html__('Show', 'lasoon'),
				'disable'  => esc_html__('Hide', 'lasoon'),
				'class'    => 'switch_box',
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( '', 'lasoon' ),
			),

			array(
				'title'     => esc_html__( 'Heading Subtitle', 'lasoon' ),
				'desc'      => esc_html__( 'Set heading subtitle for your coming soon page.', 'lasoon' ),
				'id'        => 'lasoon_heading_subtitle',
				'default'   => esc_html__("Our website is under construction. We are working very hard to give you the best experience with this one."),
				'desc_tip'  => true,
				'rows'   	=> 1,
				'type'      => 'texteditor',
			),

			array(
				'title'    => esc_html__( 'Footer Copyright', 'lasoon' ),
				'desc'     => esc_html__( 'Set footer copyright for your coming soon page.', 'lasoon' ),
				'id'       => 'lasoon_footer_copyright',
				'default'  => esc_html__("Copyright 2022 © The_Krishna . All Right Reserved"),
				'desc_tip' => true,
				'rows'     => 1,
				'type'     => 'texteditor',
			),

			array(
				'title'    => esc_html__( 'Countdown Enable', 'lasoon' ),
				'desc'     => esc_html__( '', 'lasoon' ),
				'id'       => 'lasoon_countdown_enable',
				'default'  => esc_html__('Unable', 'lasoon'),
				'unable'   => esc_html__('Show', 'lasoon'),
				'disable'  => esc_html__('Hide', 'lasoon'),
				'class'    => 'switch_box',
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( '', 'lasoon' ),
			),

			array(
				'title'    => esc_html__( 'Launch Date', 'lasoon' ),
				'desc'     => esc_html__( 'Set launch date for your coming soon page.', 'lasoon' ),
				'id'       => 'lasoon_launch_date',
				'default'  => esc_html__(""),
				'desc_tip' => true,
				'type'     => 'datetime-local',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'blog_page_settings',
			),

			array(
				'title' => esc_html__( 'Social Icon Urls', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__('This section is specially for Change Social links.', 'lasoon'),
				'id'    => 'social_links_title',
			),
			array(
				'id' => 'facebook_social_link',
				'type' => 'url',
				'title' => esc_html__('Facebook', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),
			array(
				'id' => 'twitter_social_link',
				'type' => 'url',
				'title' => esc_html__('Twitter', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),
			array(
				'id' => 'instagram_social_link',
				'type' => 'url',
				'title' => esc_html__('Instagram', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),	
			array(
				'id' => 'linkedin_social_link',
				'type' => 'url',
				'title' => esc_html__('Linkedin', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),
			array(
				'id' => 'whatsapp_social_link',
				'type' => 'url',
				'title' => esc_html__('Whatsapp', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),
			array(
				'id' => 'youtube_social_link',
				'type' => 'url',
				'title' => esc_html__('Youtube', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),
			array(
				'id' => 'skype_social_link',
				'type' => 'url',
				'title' => esc_html__('Skype', 'lasoon'),
				'class' => 'lasoon-loader',
				'default' => '',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'enable_disable_options',
			),


		);

return apply_filters( 'lasoon_general_settings', $settings );
}

}

return new Lasoon_Settings_General();