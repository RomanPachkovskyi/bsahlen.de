<?php
/**
 * Lasoon General Settings
 *
 * @package Lasoon\Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'Lasoon_Settings_Layout', false ) ) {
	return new Lasoon_Settings_Layout();
}
include_once dirname( __FILE__ ) . '/class-lasoon-setting-page.php';

/**
 * Lasoon_Admin_Settings_General.
 */
class Lasoon_Settings_Layout extends Lasoon_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'layout';
		$this->label = esc_html__( 'Layout', 'lasoon' );

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
				'title' => esc_html__( 'Main Layout', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__( 'Here you can set Coming Soon Page designs.', 'lasoon' ),
				'id'    => 'layout_page_settings',
			),

			array(
				'title'    => esc_html__( 'Layout', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set layout for blogs.', 'lasoon' ),
				'id'       => 'lasoon_blog_layout',
				'default'  => 'layout_1',
				'type'     => 'select',
				'class'    => 'lasoon-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'layout_1'  => array(esc_html__( 'Layout 1', 'lasoon' ), LASOON_PLUGIN_FILE.'images/layout_1.jpg'),
					'layout_2'  => array(esc_html__( 'Layout 2', 'lasoon' ), LASOON_PLUGIN_FILE.'images/layout_2.jpg'),
					'layout_3' => array(esc_html__( 'Layout 3', 'lasoon' ), LASOON_PLUGIN_FILE.'images/layout_3.jpg'),
					'layout_4' => array(esc_html__( 'Layout 4', 'lasoon' ), LASOON_PLUGIN_FILE.'images/layout_4.jpg'),
					'layout_5' => array(esc_html__( 'Layout 5', 'lasoon' ), LASOON_PLUGIN_FILE.'images/layout_5.jpg'),
				)
			),

			array(
				'title'    => esc_html__( '', 'lasoon' ),
				'desc'     => esc_html__( 'This Preview is for layout.', 'lasoon' ),
				'id'       => 'lasoon_blog_layout_preview',
				'type'     => 'preview_design',
				'class'    => 'lasoon-preview-design',
				'desc_tip' => true,
			),
			
			array(
				'title'    => esc_html__( 'Background Type', 'lasoon' ),
				'desc'     => esc_html__( 'Select type of background', 'lasoon' ),
				'id'       => 'lasoon_background_type',
				'default'  => esc_html__('Disable', 'lasoon'),
				'unable'   => esc_html__('Image', 'lasoon'),
				'disable'   => esc_html__('Video', 'lasoon'),
				'type'     => 'switchbox',
				'desc_tip' => esc_html__( 'Background will be change after change it.', 'lasoon' ),
			),

			array(
				'id' => 'lasoon_back_1_image',
				'type' => 'file',
				'title' => esc_html__('Slider 1 Image', 'lasoon'),
				'desc' => esc_html__('','lasoon'),
				'field_desc' => esc_html__('Select <strong> Slider Image </strong> for Background.', 'lasoon'),
				'class' => 'lasoon-loader back_img',
				'default' => '',
			),
			array(
				'id' => 'lasoon_back_2_image',
				'type' => 'file',
				'title' => esc_html__('Slider 2 Image', 'lasoon'),
				'desc' => esc_html__('','lasoon'),
				'field_desc' => esc_html__('Select <strong> Slider Image </strong> for Background.', 'lasoon'),
				'class' => 'lasoon-loader back_img',
				'default' => '',
			),
			array(
				'id' => 'lasoon_back_3_image',
				'type' => 'file',
				'title' => esc_html__('Slider 3 Image', 'lasoon'),
				'desc' => esc_html__('','lasoon'),
				'field_desc' => esc_html__('Select <strong> Slider Image </strong> for Background.', 'lasoon'),
				'class' => 'lasoon-loader back_img',
				'default' => '',
			),

			array(
				'title'    => esc_html__( 'Video Option', 'lasoon' ),
				'desc'     => esc_html__( 'Select type of video background', 'lasoon' ),
				'id'       => 'lasoon_background_video_type',
				'default'  => esc_html__('Disable', 'lasoon'),
				'unable'   => esc_html__('Upload', 'lasoon'),
				'disable'   => esc_html__('Embed', 'lasoon'),
				'type'     => 'switchbox',
				'class'    => 'video_type_select',
				'desc_tip' => esc_html__( 'Background will be change after change it.', 'lasoon' ),
			),

			array(
				'id' => 'lasoon_back_video',
				'type' => 'file',
				'title' => esc_html__('Background Video', 'lasoon'),
				'desc' => esc_html__('','lasoon'),
				'field_desc' => esc_html__('Select <strong> Video </strong> for Background.', 'lasoon'),
				'class' => 'lasoon-loader back_video',
				'default' => '',
			),

			array(
				'id' => 'lasoon_embed_url',
				'type' => 'text',
				'title' => esc_html__('Youtube/Vimeo Iframe', 'lasoon'),
				'class' => 'lasoon-loader embed_url',
				'default' => '',
			),

			array(
				'type' => 'sectionend',
				'id'   => 'layout_page_settings',
			),
			array(
				'title' => esc_html__( 'Animation', 'lasoon' ),
				'type'  => 'title',
				'desc'  => esc_html__( 'Here you can set Animation designs.', 'lasoon' ),
				'id'    => 'layout_page_settings_animation',
			),
			array(
				'title'    => esc_html__( 'Background Animation', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set animation for Background.', 'lasoon' ),
				'id'       => 'lasoon_back_animation',
				'default'  => esc_html__('particles', 'lasoon'),
				'type'     => 'select',
				'class'    => 'lasoon-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'none'    => esc_html__( 'None', 'lasoon' ),
					'fire_ball'    => esc_html__( 'Fire Ball', 'lasoon' ),
					'snow-rain'    => esc_html__( 'Snow Rain', 'lasoon' ),
					'magical_particles'    => esc_html__( 'Magical Particles', 'lasoon' ),
					'lighting_ball' => esc_html__( 'Lighting Ball', 'lasoon' ),
					'particles'  => esc_html__( 'Particles', 'lasoon' ),
					'ripple'  => esc_html__( 'Ripple', 'lasoon' ),
					'particle_waves'  => esc_html__( 'Particle Waves', 'lasoon' ),
					'confetti'  => esc_html__( 'Confetti', 'lasoon' ),
					'constellation_particle'  => esc_html__( 'Constellation Particle', 'lasoon' ),
					'fireworks'  => esc_html__( 'Fireworks', 'lasoon' ),
					'particle_fields'  => esc_html__( 'Particle Fields', 'lasoon' ),
					'bubble_particle'  => esc_html__( 'Bubble Particle', 'lasoon' ),
					'shapes'  => esc_html__( 'Shapes', 'lasoon' ),
					'shooting_star'  => esc_html__( 'Shooting Star', 'lasoon' ),
					'projector'  => esc_html__( 'Projector', 'lasoon' ),
					'rain_matrix'  => esc_html__( 'Rain Matrix', 'lasoon' ),
					'rain_matrix_two'  => esc_html__( 'Rain Matrix Two', 'lasoon' ),
					'rain_matrix_three'  => esc_html__( 'Rain Matrix Three', 'lasoon' ),
					'matrix_cell'  => esc_html__( 'Matrix Cell', 'lasoon' ),
					'growing_bubbles'  => esc_html__( 'Growing Bubbles', 'lasoon' ),
					'bouncing_ball'  => esc_html__( 'Bouncing Ball', 'lasoon' ),
					'circle_particle'  => esc_html__( 'Circle Particle', 'lasoon' ),
					'cloud'  => esc_html__( 'Cloud', 'lasoon' ),
					'infinit_tunnel'  => esc_html__( 'Infinit Tunnel', 'lasoon' ),
					'triangle'  => esc_html__( 'Triangle', 'lasoon' ),
					'moving_star'  => esc_html__( 'Moving Star', 'lasoon' ),
					'shine_mozaic'  => esc_html__( 'Shine Mozaic', 'lasoon' ),
					'hawking'  => esc_html__( 'Hawking', 'lasoon' ),
					'space_war'  => esc_html__( 'Space War', 'lasoon' ),
					'constellation'  => esc_html__( 'Constellation', 'lasoon' ),
					'space'  => esc_html__( 'Space', 'lasoon' ),
					'color_birds'  => esc_html__( 'Color Birds', 'lasoon' ),
					'animated_background'  => esc_html__( 'Animated Background', 'lasoon' ),
					'line_waves'  => esc_html__( 'Line Waves', 'lasoon' ),
					'topology'  => esc_html__( 'Topology', 'lasoon' ),
					'halo'  => esc_html__( 'Halo', 'lasoon' ),
					'smoke_simulation'  => esc_html__( 'Smoke Simulation', 'lasoon' ),
					'rainbow_box'  => esc_html__( 'Rainbow Box', 'lasoon' ),
					'neon_rain'  => esc_html__( 'Neon Rain', 'lasoon' ),
					'color_birds_two'  => esc_html__( 'Color Birds Two', 'lasoon' ),
					'fog'  => esc_html__( 'Fog', 'lasoon' ),
					'particle_net'  => esc_html__( 'Particle Net', 'lasoon' ),
					'ripple_cell'  => esc_html__( 'Ripple Cell', 'lasoon' ),
					'swash_bubble'  => esc_html__( 'Swash Bubble', 'lasoon' ),
					'interactive_background'  => esc_html__( 'Interactive Background', 'lasoon' ),
					'chewing_gum'  => esc_html__( 'Chewing Gum', 'lasoon' ),
					'univers'  => esc_html__( 'Univers', 'lasoon' ),
					'squidematic'  => esc_html__( 'Squidematic', 'lasoon' ),
					'orbit_lines'  => esc_html__( 'Orbit Lines', 'lasoon' ),
					'hexagon_forming'  => esc_html__( 'Hexagon Forming', 'lasoon' ),
					'particle_tails'  => esc_html__( 'Particle Tails', 'lasoon' ),
					'particle_tails_two'  => esc_html__( 'Particle Tails Two', 'lasoon' ),
					'ribbons'  => esc_html__( 'Ribbons', 'lasoon' ),
					'rotate_spiral'  => esc_html__( 'Rotate Spiral', 'lasoon' ),
					'birds_three'  => esc_html__( 'Birds Three', 'lasoon' ),
					'particle_random'  => esc_html__( 'Particle Random', 'lasoon' ),
					'physics_particle'  => esc_html__( 'Physics Particle', 'lasoon' ),
				)
			),

			array(
				'title'    => esc_html__( 'Heading Animation', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set animation for Heading Title.', 'lasoon' ),
				'id'       => 'lasoon_heading_animation',
				'default'  => esc_html__('animate_1', 'lasoon'),
				'type'     => 'select',
				'class'    => 'lasoon-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'none'    => esc_html__( 'None', 'lasoon' ),
					'line_up'    => esc_html__( 'Line Up', 'lasoon' ),
					'text_flip'  => esc_html__( 'Text Flip', 'lasoon' ),
					'top_bottom' => esc_html__( 'Top Bottom', 'lasoon' ),
					'bounce' => esc_html__( 'Bounce', 'lasoon' ),
					'blink' => esc_html__( 'Blink', 'lasoon' ),
					'jello' => esc_html__( 'Jello', 'lasoon' ),
					'shining_text' => esc_html__( 'Shining Text', 'lasoon' ),
					'text_shake' => esc_html__( 'Text Shake', 'lasoon' ),
					'glitch' => esc_html__( 'Glitch', 'lasoon' ),
					'strip_fill' => esc_html__( 'Strip Fill', 'lasoon' ),
					'text_shadow' => esc_html__( 'Text Shadow', 'lasoon' ),
					'glitch_two' => esc_html__( 'Glitch Two', 'lasoon' ),
					'color_text' => esc_html__( 'Color Text', 'lasoon' ),
					'spotlight' => esc_html__( 'Spotlight', 'lasoon' ),
					'spooky' => esc_html__( 'Spooky', 'lasoon' ),
					'sparkle_bottom' => esc_html__( 'Sparkle Bottom', 'lasoon' ),
					'woop' => esc_html__( 'Woop', 'lasoon' ),
					'luminace' => esc_html__( 'Luminace', 'lasoon' ),
					'cinematic' => esc_html__( 'Cinematic', 'lasoon' ),
					'bouncy_right_text' => esc_html__( 'Bouncy Right Text', 'lasoon' ),
					'moving_text' => esc_html__( 'Moving Text', 'lasoon' ),
					'slowmo_text' => esc_html__( 'Slowmo Text', 'lasoon' ),
					'bottom_wavy_text' => esc_html__( 'Bottom Wavy Text', 'lasoon' ),
					'corner_down' => esc_html__( 'Corner Down', 'lasoon' ),
					'bounce_slide_up' => esc_html__( 'Bounce Slide Up', 'lasoon' ),
					'slide_in_left' => esc_html__( 'Slide In Left', 'lasoon' ),
					'rising' => esc_html__( 'Rising', 'lasoon' ),
					'fade_in_slide' => esc_html__( 'Fade In Slide', 'lasoon' ),
					'line_shadow' => esc_html__( 'Line Shadow', 'lasoon' ),
					'threed_text' => esc_html__( 'Threed Text', 'lasoon' ),
				)
			),

			array(
				'title'    => esc_html__( 'Counter Shape', 'lasoon' ),
				'desc'     => esc_html__( 'This option lets you set shapes for Counter.', 'lasoon' ),
				'id'       => 'lasoon_shape_animation',
				'default'  => esc_html__('dash_circle', 'lasoon'),
				'type'     => 'select',
				'class'    => 'lasoon-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'dash_circle'    =>array(esc_html__( 'Dash Circle', 'lasoon' ), LASOON_PLUGIN_FILE.'images/dash_circle.jpg'),
					'dash_diamond'  => array(esc_html__( 'Dash Diamond', 'lasoon' ),  LASOON_PLUGIN_FILE.'images/dash_diamond.jpg'),
					'glass_circle'  =>  array(esc_html__( 'Glass Circle', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_circle.jpg'),
					'glass_square'  => array(esc_html__( 'Glass Square', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_square.jpg'),
					'glass_diamond'  => array(esc_html__( 'Glass Diamond', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_diamond.jpg'),
					'glass_square_note'  => array(esc_html__( 'Glass Square Note', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_square_note.jpg'),
					'glass_pentagone'  => array(esc_html__( 'Glass Pentagone', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_pentagone.jpg'),
					'glass_hexagonal'  => array(esc_html__( 'Glass Hexagonal', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_hexagonal.jpg'),
					'glass_heptagon'  => array(esc_html__( 'Glass Heptagon', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_heptagon.jpg'),
					'glass_octagon'  => array(esc_html__( 'Glass Octagon', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_octagon.jpg'),
					'glass_decagon'  => array(esc_html__( 'Glass Decagon', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_decagon.jpg'),
					'glass_bevel'  => array(esc_html__( 'Glass Bevel', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_bevel.jpg'),
					'glass_rabbet'  => array(esc_html__( 'Glass Rabbet', 'lasoon' ), LASOON_PLUGIN_FILE.'images/glass_rabbet.jpg'),
					'diamond_inline'  => array(esc_html__( 'Diamond Inline', 'lasoon' ), LASOON_PLUGIN_FILE.'images/diamond_inline.jpg'),
					'square_note'  => array(esc_html__( 'Square Note', 'lasoon' ), LASOON_PLUGIN_FILE.'images/square_note.jpg'),
					'circle_note'  => array(esc_html__( 'Circle Note', 'lasoon' ), LASOON_PLUGIN_FILE.'images/circle_note.jpg'),
					'square_corner_note'  => array(esc_html__( 'Square Corner Note', 'lasoon' ), LASOON_PLUGIN_FILE.'images/square_corner_note.jpg'),
					'fill_pentagone'  => array(esc_html__( 'fill Pentagone', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_pentagone.jpg'),
					'fill_hexagonal'  => array(esc_html__( 'Fill Hexagonal', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_hexagonal.jpg'),
					'fill_heptagon'  => array(esc_html__( 'Fill Heptagon', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_heptagon.jpg'),
					'fill_octagon'  => array(esc_html__( 'Fill Octagon', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_octagon.jpg'),
					'fill_decagon'  => array(esc_html__( 'Fill Decagon', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_decagon.jpg'),
					'fill_bevel'  => array(esc_html__( 'Fill Bevel', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_bevel.jpg'),
					'fill_rabbet'  => array(esc_html__( 'Fill Rabbet', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_rabbet.jpg'),
					'fill_square'  => array(esc_html__( 'Fill Square', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_square.jpg'),
					'side_border'  => array(esc_html__( 'Side Border', 'lasoon' ), LASOON_PLUGIN_FILE.'images/side_border.jpg'),
					'stroke_square'  => array(esc_html__( 'Stroke Square', 'lasoon' ), LASOON_PLUGIN_FILE.'images/stroke_square.jpg'),
					'stroke_square_two'  => array(esc_html__( 'Stroke Square Two', 'lasoon' ), LASOON_PLUGIN_FILE.'images/stroke_square_two.jpg'),
					'stroke_square_three'  => array(esc_html__( 'Stroke Square Three', 'lasoon' ), LASOON_PLUGIN_FILE.'images/stroke_square_three.jpg'),
					'stroke_square_four'  => array(esc_html__( 'Stroke Square Four', 'lasoon' ), LASOON_PLUGIN_FILE.'images/stroke_square_four.jpg'),
					'fill_counter'  => array(esc_html__( 'Fill Counter', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_counter.jpg'),
					'fill_counter_two'  => array(esc_html__( 'Fill Counter Two', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_counter_two.jpg'),
					'flip_counter'  => array(esc_html__( 'Flip Counter', 'lasoon' ), LASOON_PLUGIN_FILE.'images/flip_counter.jpg'),
					'flip_counter_two'  => array(esc_html__( 'Flip Counter Two', 'lasoon' ), LASOON_PLUGIN_FILE.'images/flip_counter_two.jpg'),
					'circle_line_counter'  => array(esc_html__( 'Circle Line Counter', 'lasoon' ), LASOON_PLUGIN_FILE.'images/circle_line_counter.jpg'),
					'fill_circle_line'  => array(esc_html__( 'Fill Circle Line', 'lasoon' ), LASOON_PLUGIN_FILE.'images/fill_circle_line.jpg'),
					'countdown_clock'  => array(esc_html__( 'Countdown Clock', 'lasoon' ), LASOON_PLUGIN_FILE.'images/countdown_clock.jpg'),
				)
			),
array(
	'title'    => esc_html__( '', 'lasoon' ),
	'desc'     => esc_html__( 'This Preview is for layout.', 'lasoon' ),
	'id'       => 'lasoon_shape_animation_preview',
	'type'     => 'preview_design',
	'class'    => 'lasoon-preview-design',
	'desc_tip' => true,
),


array(
	'type' => 'sectionend',
	'id'   => 'layout_page_settings_animation',
),

array(
	'title' => esc_html__( 'Email Template', 'lasoon' ),
	'type'  => 'title',
	'desc'  => esc_html__( 'This email template is for Sending alert for Live Mode to Subscribers.', 'lasoon' ),
	'id'    => 'layout_page_settings_email_template',
),


array(
	'id' => 'lasoon_back_image_for_email',
	'type' => 'file',
	'title' => esc_html__('Email Background Image', 'lasoon'),
	'desc' => esc_html__('','lasoon'),
	'field_desc' => esc_html__('Select <strong> Background Image </strong> for Email.', 'lasoon'),
	'class' => 'lasoon-loader',
	'default' => '',
),


array(
	'title'     => esc_html__( 'Email Heading Title', 'lasoon' ),
	'desc'      => esc_html__( 'Set heading title for your email template.', 'lasoon' ),
	'id'        => 'lasoon_heading_title_for_email',
	'default'   => esc_html__("Visit Our New Website", 'lasoon'),
	'desc_tip'  => true,
	'type'      => 'text',
),

array(
	'title'     => esc_html__( 'Email Heading Subtitle', 'lasoon' ),
	'desc'      => esc_html__( 'Set heading subtitle for your email template.', 'lasoon' ),
	'id'        => 'lasoon_heading_subtitle_for_email',
	'default'   => esc_html__("See better than ever how lasoon can help you...", 'lasoon'),
	'desc_tip'  => true,
	'type'      => 'text',
),

array(
	'title'     => esc_html__( 'Email Button Text', 'lasoon' ),
	'desc'      => esc_html__( 'Set Button Text for your email template.', 'lasoon' ),
	'id'        => 'lasoon_button_text_for_email',
	'default'   => esc_html__("Visit Now", 'lasoon'),
	'desc_tip'  => true,
	'type'      => 'text',
),

array(
	'title'     => esc_html__( 'Email Subject', 'lasoon' ),
	'desc'      => esc_html__( 'Set Subject for your email reminder.', 'lasoon' ),
	'id'        => 'lasoon_subject_for_email',
	'default'   => esc_html__("Our site is Launched", 'lasoon'),
	'desc_tip'  => true,
	'type'      => 'text',
),

array(
	'title'     => esc_html__( 'Send Reminder', 'lasoon' ),
	'desc'      => esc_html__( 'Here you can send Reminder to Subscription Users When Site is Live.', 'lasoon' ),
	'id'        => 'lasoon_send_reminder',
	'class'     => 'lasoon_send_reminder',
	'default'   => esc_html__("Send Reminder", 'lasoon'),
	'desc_tip'  => false,
	'type'      => 'button',
),

array(
	'type' => 'sectionend',
	'id'   => 'layout_page_settings_email_template',
),

array(
	'id'       => 'lasoon_email_preview',
	'type'     => 'preview',
	'class'    => 'lasoon-enhanced-select',				
),
);

return apply_filters( 'lasoon_layout_settings', $settings );
}
}

return new Lasoon_Settings_Layout();