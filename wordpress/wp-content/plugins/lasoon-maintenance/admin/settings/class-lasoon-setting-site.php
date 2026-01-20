<?php
/**
 * Lasoon General Settings
 *
 * @package Lasoon\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Lasoon_Settings_Site', false ) ) {
	return new Lasoon_Settings_Site();
}
include_once dirname( __FILE__ ) . '/class-lasoon-setting-page.php';
/**
 * Lasoon_Admin_Settings_General.
 */
class Lasoon_Settings_Site extends Lasoon_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'site';
		$this->label = esc_html__( 'Settings', 'lasoon' );

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
				'title' => esc_html__( 'Site Settings', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__( 'Here you can set Site Settings.', 'lasoon' ),
				'id'    => 'site_page_settings',
			),

			array(
				'title'    => esc_html__( 'Maintenance Mode', 'lasoon' ),
				'desc'     => esc_html__( 'Enable Maintenance Mode', 'lasoon' ),
				'id'       => 'lasoon_maintenance_mode',
				'default'  => 'Disable',
				'unable'   => esc_html__('Activate', 'lasoon'),
				'disable'   => esc_html__('Deactivate', 'lasoon'),
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'Activate and Deactiavte Maintenance Mode.', 'lasoon' ),
			),


			array(
				'title'    => esc_html__( 'Exclude Backend Role', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set exclude backend roles.', 'lasoon' ),
				'id'       => 'lasoon_exclude_backend',
				'type'     => 'selectrole',
				'class'    => 'lasoon-chosen-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
			),


			array(
				'title'    => esc_html__( 'Exclude Frontend Role', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set exclude frontend roles.', 'lasoon' ),
				'id'       => 'lasoon_exclude_frontend',
				'type'     => 'selectrole',
				'class'    => 'lasoon-chosen-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
			),

			array(
				'title'    => esc_html__( 'Exclude Pages', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set exclude pages.', 'lasoon' ),
				'id'       => 'lasoon_exclude_page',
				'type'     => 'selectpage',
				'class'    => 'lasoon-chosen-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
			),

			array(
				'type' => 'sectionend',
				'id'   => 'site_page_settings',
			),

			array(
				'title' => esc_html__( 'SEO Settings', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__( 'Here you can set SEO Settings.', 'lasoon' ),
				'id'    => 'site_page_seo_settings',
			),

			array(
				'title'             => esc_html__( 'Title', 'lasoon' ),
				'desc'              => esc_html__( 'Set title for your coming soon page.', 'lasoon' ),
				'id'                => 'lasoon_seo_title',
				'default'           => esc_html__("Lasoon Maintenance", 'lasoon'),
				'desc_tip'          => true,
				'type'              => 'text',
			),

			array(
				'title'    => esc_html__( 'Meta Robot', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set Meta Robot.', 'lasoon' ),
				'id'       => 'lasoon_meta_robot',
				'default'  => 'noindex',
				'type'     => 'select',
				'class'    => 'lasoon-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'index'        => array(esc_html__( 'Index, Follow', 'lasoon' )),
					'noindex'        => array(esc_html__( 'Noindex, Nofollow', 'lasoon' )),
				),
			),

			array(
				'type' => 'sectionend',
				'id'   => 'site_page_seo_settings',
			),

		);

		return apply_filters( 'lasoon_site_settings', $settings );
	}
}

return new Lasoon_Settings_Site();