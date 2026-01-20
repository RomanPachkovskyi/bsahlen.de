<?php
/**
 * Lasoon Admin Settings Class
 *
 * @package  Lasoon\Admin
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Lasoon_Admin_Settings', false ) ) :

	/**
	 * Lasoon_Admin_Settings Class.
	 */
	class Lasoon_Admin_Settings {

		/**
		 * Setting pages.
		 *
		 * @var array
		 */
		private static $settings = array();

		/**
		 * Error messages.
		 *
		 * @var array
		 */
		private static $errors = array();

		/**
		 * Update messages.
		 *
		 * @var array
		 */
		private static $messages = array();

		/**
		 * Include the settings page classes.
		 */
		public static function get_settings_pages() {
			if ( empty( self::$settings ) ) {
				$settings = array();
				include_once dirname( __FILE__ ) . '/settings/class-lasoon-setting-page.php';

				$settings[] = include __DIR__ . '/settings/class-lasoon-setting-general.php';
				$settings[] = include __DIR__ . '/settings/class-lasoon-setting-about.php';
				$settings[] = include __DIR__ . '/settings/class-lasoon-setting-layout.php';
				$settings[] = include __DIR__ . '/settings/class-lasoon-setting-site.php';
				self::$settings = apply_filters( 'lasoon_get_settings_pages', $settings );
			}
			return self::$settings;
		}

		/**
		 * Save the settings.
		 */
		public static function save() {
			global $current_tab;
			$current_tab = (isset($_GET['tab']))?$_GET['tab']:'general';

			check_admin_referer( 'lasoon-settings' );
			// Trigger actions.
			do_action( 'lasoon_settings_save_' . $current_tab );
			do_action( 'lasoon_update_options_' . $current_tab );
			do_action( 'lasoon_update_options' );

			self::add_message( esc_html__( 'Your settings have been saved.', 'lasoon' ) );
			// self::check_download_folder_protection();

			// Clear any unwanted data and flush rules.
			update_option( 'lasoon_queue_flush_rewrite_rules', 'yes' );
			
			do_action( 'lasoon_settings_saved' );
		}

		/**
		 * Add a message.
		 *
		 * @param string $text Message.
		 */
		public static function add_message( $text ) {
			self::$messages[] = $text;
		}

		/**
		 * Add an error.
		 *
		 * @param string $text Message.
		 */
		public static function add_error( $text ) {
			self::$errors[] = $text;
		}

		/**
		 * Output messages + errors.
		 */
		public static function show_messages() {
			if ( count( self::$errors ) > 0 ) {
				foreach ( self::$errors as $error ) {
					echo '<div id="message" class="error inline"><p><strong>' . esc_html__( $error, 'lasoon' ) . '</strong></p></div>';
				}
			} elseif ( count( self::$messages ) > 0 ) {
				foreach ( self::$messages as $message ) {
					echo '<div id="message" class="updated inline"><p><strong>' . esc_html__( $message , 'lasoon') . '</strong></p></div>';
				}
			}
		}

		/**
		 * Settings page.
		 *
		 * Handles the display of the main lasoon settings page in admin.
		 */
		public static function output() { 
			global $current_section, $current_tab;

			$current_tab = (isset($_REQUEST['tab']))?$_REQUEST['tab'] : 'general';
			$current_tab = esc_attr($current_tab);			

			do_action( 'lasoon_settings_start' );

			// Get tabs for the settings page.
			$tabs = apply_filters( 'lasoon_settings_tabs_array', array());

			include dirname( __FILE__ ) . '/views/lasoon-admin-page.php';
		}

		/**
		 * Get a setting from the settings API.
		 *
		 * @param string $option_name Option name.
		 * @param mixed  $default     Default value.
		 * @return mixed
		 */
		public static function get_option( $option_name, $default = '' ) {
			if ( ! $option_name ) {
				return $default;
			}

			// Array value.
			if ( strstr( $option_name, '[' ) ) {

				parse_str( $option_name, $option_array );

				// Option name is first key.
				$option_name = current( array_keys( $option_array ) );

				// Get value.
				$option_values = get_option( $option_name, '' );

				$key = key( $option_array[ $option_name ] );

				if ( isset( $option_values[ $key ] ) ) {
					$option_value = $option_values[ $key ];
				} else {
					$option_value = null;
				}
			} else {
				// Single value.
				$option_value = get_option( $option_name, null );
			}

			if ( is_array( $option_value ) ) {
				$option_value = wp_unslash( $option_value );
			} elseif ( ! is_null( $option_value ) ) {
				$option_value = stripslashes( $option_value );
			}

			return ( null === $option_value ) ? $default : $option_value;
		}

		/**
		 * Output admin fields.
		 *
		 * Loops through the lasoon options array and outputs each field.
		 *
		 * @param array[] $options Opens array to output.
		 */
		public static function output_fields( $options ) {
			foreach ( $options as $value ) {
				if ( ! isset( $value['type'] ) ) {
					continue;
				}
				if ( ! isset( $value['id'] ) ) {
					$value['id'] = '';
				}
				if ( ! isset( $value['title'] ) ) {
					$value['title'] = isset( $value['name'] ) ? $value['name'] : '';
				}
				if ( ! isset( $value['class'] ) ) {
					$value['class'] = '';
				}
				if ( ! isset( $value['css'] ) ) {
					$value['css'] = '';
				}
				if ( ! isset( $value['default'] ) ) {
					$value['default'] = '';
				}
				if ( ! isset( $value['desc'] ) ) {
					$value['desc'] = '';
				}
				if ( ! isset( $value['desc_tip'] ) ) {
					$value['desc_tip'] = true;
				}
				if ( ! isset( $value['placeholder'] ) ) {
					$value['placeholder'] = '';
				}
				if ( ! isset( $value['suffix'] ) ) {
					$value['suffix'] = '';
				}
				if ( ! isset( $value['value'] ) ) {
					$value['value'] = self::get_option( $value['id'], $value['default'] );
				}

				// Custom attribute handling.
				$custom_attributes = array();

				if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
					foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
						$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
					}
				}

				// Description handling.
				$field_description = self::get_field_description( $value );
				$description       = $field_description['description'];
				$tooltip_html      = wp_kses_post($field_description['tooltip_html'], 'lasoon');

				// Switch based on type.
				switch ( $value['type'] ) {

					// Section Titles.
					case 'title':
					echo '<div class="lasoon_title_wrap">';
					echo '<div class="heading_wrap">';
					if ( ! empty( $value['title'] ) ) {
						echo '<h2>' . esc_html__( $value['title'], 'lasoon' ) . '</h2>';
					}
					if ( ! empty( $value['desc'] ) ) {
						echo '<div id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-description">';
						echo wp_kses_post( wpautop( wptexturize( $value['desc'] ) ) );
						echo '</div>';
					}
					echo '</div>';
					
					echo '</div>';

					echo '<table class="form-table" id="table-'.esc_attr($value['id']).'">' . "\n\n";
					if ( ! empty( $value['id'] ) ) {
						do_action( 'lasoon_settings_' . sanitize_title( $value['id'] ) );
					}
					break;

					// Section Ends.
					case 'sectionend':
					if ( ! empty( $value['id'] ) ) {
						do_action( 'lasoon_settings_' . sanitize_title( $value['id'] ) . '_end' );
					}
					echo '</table>';
					if ( ! empty( $value['id'] ) ) {
						do_action( 'lasoon_settings_' . sanitize_title( $value['id'] ) . '_after' );
					}
					break;

					// Standard text inputs and subtypes like 'number'.

					case 'number':
					case 'text':
					case 'url':
					case 'tel':
					case 'email':
					$option_value = $value['value'];

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'], 'lasoon' ); ?> </label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<input
							name="<?php echo esc_attr( $value['id'] ); ?>"
							id="<?php echo esc_attr( $value['id'] ); ?>"
							type="<?php echo esc_attr( $value['type'] ); ?>"
							style="<?php echo esc_attr( $value['css'] ); ?>"
							value="<?php echo esc_attr( $option_value ); ?>"
							class="<?php echo esc_attr( $value['class'] ); ?>"
							placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
							<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
							/><?php echo esc_html__( $value['suffix'], 'lasoon' ); ?> <?php echo wp_kses_post($description, 'lasoon'); // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

					case 'texteditor':
					$option_value = $value['value'];
					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'], 'lasoon' ); ?> </label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<?php 
							wp_editor( $option_value, esc_attr( $value['id'] ), $settings = array('media_buttons'=>false, 'quicktags' => false, 'tinymce' => array(
								'toolbar1' => 'bold, italic, underline',
								'toolbar2'=>false
							),'textarea_rows'=> $value['rows']) ); ?>
						</td>
					</tr>
					<?php
					break;

					case 'datetime-local':
					$option_value = $value['value'];

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'], 'lasoon' ); ?> </label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<input
							name="<?php echo esc_attr( $value['id'] ); ?>"
							id="<?php echo esc_attr( $value['id'] ); ?>"
							type="<?php echo esc_attr( $value['type'] ); ?>"
							style="<?php echo esc_attr( $value['css'] ); ?>"
							value="<?php echo esc_attr( $option_value ); ?>"
							class="<?php echo esc_attr( $value['class'] ); ?>"
							placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
							<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
							/><?php echo esc_html__( $value['suffix'], 'lasoon' ); ?> <?php echo wp_kses_post($description, 'lasoon'); // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

					// Select boxes.
					case 'select':
					case 'multiselect':
					$option_value = $value['value'];	

					?>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'] , 'lasoon'); ?> </label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<select
							name="<?php echo esc_attr( $value['id'] ); ?><?php echo ( 'multiselect' === $value['type'] ) ? '[]' : ''; ?>"
							id="<?php echo esc_attr( $value['id'] ); ?>"
							style="<?php echo esc_attr( $value['css'] ); ?>"
							class="<?php echo esc_attr( $value['class'] ); ?>"
							<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
							<?php echo 'multiselect' === esc_attr($value['type']) ? 'multiple="multiple"' : ''; ?>
							>
							<?php
							foreach ( $value['options'] as $key => $val ) {
								?>
								<option data-img="<?php echo (isset($val[1]))?$val[1]:''; ?>" value="<?php echo esc_attr( $key ); ?>"
									<?php

									if ( is_array( $option_value ) ) {
										selected( in_array( (string) $key, $option_value, true ), true );
									} else {
										selected( $option_value, (string) $key );
									}

									?>
									><?php echo is_array($val)? $val[0] : esc_html__( $val , 'lasoon'); ?></option>
									<?php
								}
								?>
								</select> <?php echo wp_kses_post($description, 'lasoon'); // WPCS: XSS ok. ?>
							</td>
						</tr>
						<?php
						break;

						case 'selectrole':
						global $wp_roles;
						$option_value = $value['value']; 
						?>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'] , 'lasoon'); ?> </label>
							</th>

							<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
								
								<select name="<?php echo esc_attr( $value['id'] ); ?>[]" multiple="multiple" class="<?php echo esc_attr( $value['class'] ); ?>" data-placeholder="<?php esc_attr_e( 'Select role(s)', 'lasoon' ); ?>">
									
									<?php
									foreach (  $wp_roles->roles as $role_key => $role_name ) { 
										if(is_array($option_value) && in_array($role_key, $option_value)) { ?>
											<option value="<?php echo esc_attr( $role_key ); ?>" selected="selected"><?php echo esc_html__( $role_name['name'] ); ?></option>
										<?php } else { ?>
											<option value="<?php echo esc_attr( $role_key ); ?>"><?php echo esc_html__( $role_name['name'] ); ?></option>
										<?php }
									}
									?>
									</select> <?php echo wp_kses_post($description, 'lasoon'); // WPCS: XSS ok. ?>
									
								</td>
							</tr>

							<?php 
							break;								

							case 'selectpage':
							$option_value = $value['value']; 
							?>
							<tr valign="top">
								<th scope="row" class="titledesc">
									<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'] , 'lasoon'); ?> </label>
								</th>
								<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
									<select name="<?php echo esc_attr( $value['id'] ); ?>[]" multiple="multiple" class="<?php echo esc_attr( $value['class'] ); ?>" data-placeholder="<?php esc_attr_e( 'Select Page(s)', 'lasoon' ); ?>">
										<?php
										$pages = get_pages(); 
										foreach (  $pages as $page ) { 
											if(is_array($option_value) && in_array($page->post_name, $option_value) ){

												?>
												<option value="<?php echo  $page->post_name; ?>" selected="selected"><?php echo $page->post_title; ?></option>
												<?php
											} else { ?>
												<option value="<?php echo  $page->post_name; ?>"><?php echo $page->post_title; ?></option>
											<?php }
										}
										?>
										</select> <?php echo wp_kses_post($description, 'lasoon'); // WPCS: XSS ok. ?>
									</td>
								</tr>

								<?php
								break;

								case 'switchbox':
								$option_value = esc_attr($value['value']);

								?>
								<tr valign="top">
									<th scope="row" class="titledesc">
										<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'], 'lasoon' ); ?></label>
									</th>
									<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
										<fieldset>


											<div class="switch-field">
												<input 
												type="radio" 
												id="<?php echo esc_attr( $value['id'] ); ?>-unable"
												class="<?php echo esc_attr( $value['class'] ); ?>  switch-on"
												name="<?php echo esc_attr( $value['id'] ); ?>" 
												style="<?php echo esc_attr( $value['css'] ); ?>"
												<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
												value="Unable" <?php echo ($option_value == 'Disable') ? '' :esc_attr('checked'); ?>/>

												<label for="<?php echo esc_attr( $value['id'] ); ?>-unable" class="left-check"><?php echo isset($value['unable'])?$value['unable']:esc_html__('On'); ?></label>

												<input 
												type="radio"
												id="<?php echo esc_attr( $value['id'] ); ?>-disable"
												class="<?php echo esc_attr( $value['class'] ); ?> switch-off"
												name="<?php echo esc_attr( $value['id'] ); ?>"
												style="<?php echo esc_attr( $value['css'] ); ?>"
												<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
												value="Disable" <?php echo ($option_value == 'Disable') ? 'checked' :''; ?>/>
												<label for="<?php echo esc_attr( $value['id'] ); ?>-disable" class="right-check"><?php echo isset($value['disable'])?$value['disable']:esc_html__('Off'); ?></label> 
												<div id="flap"><span class="content"></span></div>
											</div>
											<?php echo $description; // WPCS: XSS ok. ?>
										</fieldset>
									</td>
								</tr>
								<?php
								break;
								case 'file':
								$option_value = esc_attr($value['value']);

								?>
								<tr valign="top">
									<th scope="row" class="titledesc">
										<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'] , 'lasoon'); ?></label>
									</th>
									<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
										<fieldset>
											<?php 

											if ($option_value == '') {
												$remove_escaped = 'style=display:none;';
												$upload_escaped = false;
											} else {
												$remove_escaped = '';
												$upload_escaped = 'style=display:none;';
											}

											?>
											<div class="file-upload-field">
												<input type="text"
												value="<?php echo esc_attr($option_value); ?>"
												name="<?php echo esc_attr( $value['id'] ); ?>"
												class="<?php echo esc_attr( $value['class'] ); ?>" />
												<a href="javascript:void(0);" data-choose="Choose a File" data-update="Select File" class="lasoon-upload" <?php echo esc_attr($upload_escaped); ?>><span></span><?php echo esc_html__('Browse', 'lasoon'); ?></a>
												<a href="javascript:void(0);" class="lasoon-upload-remove" <?php echo esc_attr($remove_escaped); ?>><?php echo esc_html__('Remove Upload', 'lasoon'); ?></a>
											</div>
										</fieldset>
									</td>
								</tr>
								<?php 
								break;
								case 'preview_design':
								?>
								<tr valign="top">
									<th scope="row" class="titledesc">
										<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'], 'lasoon' ); ?></label>
									</th>
									<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
										<fieldset>
											<div class="<?php echo esc_attr( $value['class'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>">
												<?php 
												$select_id =  str_replace("_preview","",$value['id']);
												$option_selected_value = esc_attr(get_option($select_id)); 

												if($option_selected_value){
													$previewimg = LASOON_PLUGIN_FILE.'images/'.$option_selected_value.'.jpg';
													$previewimg = $previewimg?esc_url($previewimg):'';
													echo '<img src="'.esc_url($previewimg).'" class="preview_image">';
												}
												?>


											</div>
										</fieldset>
									</td>
								</tr>
								<?php
								break;
								case 'preview':
								$back_image_email = esc_url(get_option('lasoon_back_image_for_email'))?:LASOON_PUBLIC_PATH.'assets/images/email_back.jpg';
								$logo = esc_url(get_option('lasoon_logo'))?:LASOON_PLUGIN_FILE.'public/assets/images/logo.png';
								$email_title = esc_html(get_option('lasoon_heading_title_for_email'))?:'Visit Our New Website';
								$email_subtitle = esc_html(get_option('lasoon_heading_subtitle_for_email'))?:'See better than ever how Lasoon can help you...';
								$email_button_txt = esc_html(get_option('lasoon_button_text_for_email'))?:'Visit Now';

								?>
								<div class="<?php echo esc_attr( $value['class'] ); ?>">
									<section class="email-template-content template-back-img" id="email_template" 
									style="background-image: url('<?php echo $back_image_email; ?>')">					
									<div class="site-logo">
										<a href="#" class="logo">
											<img src="<?php echo $logo; ?>" alt="site logo">
										</a>
									</div>
									<div class="email-main-content">
										<p class="live-link">live now</p>
										<h1 class="email-heading"><?php echo $email_title; ?></h1>
										<p class="email-short-desc"><?php echo $email_subtitle; ?></p>
										<button class="btn btn-visit" id="visit_now"><?php echo $email_button_txt; ?></button>
									</div>
								</section>

							</div>
							<?php 
							break;
							case 'button':
							$option_value =  esc_attr($value['value']);
							?>
							<tr valign="top">
								<th scope="row" class="titledesc">
									<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html__( $value['title'], 'lasoon' ); ?> </label>
								</th>
								<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
									<input
									name="<?php echo esc_attr( $value['id'] ); ?>"
									id="<?php echo esc_attr( $value['id'] ); ?>"
									type="<?php echo esc_attr( $value['type'] ); ?>"
									value="<?php echo esc_attr( $option_value ); ?>"
									class="<?php echo esc_attr( $value['class'] ); ?>"
									<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
									/><?php echo esc_html__( $value['suffix'], 'lasoon' ); ?> <?php echo wp_kses_post($description, 'lasoon'); // WPCS: XSS ok. ?>
									<div class="reminder_msg"></div>
								</td>
							</tr>
							<?php
							break;
							case 'divstart':
							?> <div class="<?php echo esc_attr( $value['class'] ); ?>" id="<?php echo esc_attr( $value['id'] ); ?>"> <?php
							break;
									// Default: run an action.
							default:
							do_action( 'lasoon_admin_field_' . $value['type'], $value );
							break;
						}
					}
				}

						/**
						* Helper function to get the formatted description and tip HTML for a
						* given form field. Plugins can call this when implementing their own custom
						* settings types.
						*
						* @param  array $value The form field value array.
						* @return array The description and tip as a 2 element array.
						*/
						public static function get_field_description( $value ) {
							$description  = '';
							$tooltip_html = '';

							if ( true === $value['desc_tip'] ) {
								$tooltip_html = $value['desc'];
							} elseif ( ! empty( $value['desc_tip'] ) ) {
								$description  = $value['desc'];
								$tooltip_html = $value['desc_tip'];
							} elseif ( ! empty( $value['desc'] ) ) {
								$description = $value['desc'];
							}

							if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ), true ) ) {
								$description = '<p style="margin-top:0">' . wp_kses_post( $description , 'lasoon' ) . '</p>';
							} elseif ( $description && in_array( $value['type'], array( 'checkbox' ), true ) ) {
								$description = wp_kses_post( $description, 'lasoon' );
							} elseif ( $description ) {
								$description = '<p class="description">' . wp_kses_post( $description , 'lasoon') . '</p>';
							}

							if ( $tooltip_html && in_array( $value['type'], array( 'checkbox' ), true ) ) {
								$tooltip_html = '<p class="description">' . esc_html__($tooltip_html, 'lasoon') . '</p>';
							}

							return array(
								'description'  => $description,
								'tooltip_html' => $tooltip_html,
							);
						}

	/**
	* Save admin fields.
	*
	* Loops through the lasoon options array and outputs each field.
	*
	* @param array $options Options array to output.
	* @param array $data    Optional. Data to use for saving. Defaults to $_POST.
	* @return bool
	*/
	public static function save_fields( $options, $data = null ) {
		if ( is_null( $data ) ) {
	$data = $_POST;		 // WPCS: input var okay, CSRF ok.
}
if ( empty( $data ) ) {
	return false;
}

		// Options to update will be stored here and saved later.
$update_options   = array();
$autoload_options = array();

		// Loop options and get values to save.
foreach ( $options as $option ) {
	if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) || ( isset( $option['is_option'] ) && false === $option['is_option'] ) ) {
		continue;
	}

// Get posted value.
	if ( strstr( $option['id'], '[' ) ) {
		parse_str( $option['id'], $option_name_array );
		$option_name  = current( array_keys( $option_name_array ) );
		$setting_name = key( $option_name_array[ $option_name ] );
		$raw_value    = isset( $data[ $option_name ][ $setting_name ] ) ? wp_unslash( $data[ $option_name ][ $setting_name ] ) : null;
	} else {
		$option_name  = $option['id'];
		$setting_name = '';
		$raw_value    = isset( $data[ $option['id'] ] ) ? wp_unslash( $data[ $option['id'] ] ) : null;
	}

// Format the value based on option type.
	switch ( $option['type'] ) {

		case 'select':
		$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
		if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
			$value = null;
			break;
		}
		$default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
		$value   = in_array( $raw_value, $allowed_values, true ) ? $raw_value : $default;
		break;
		default:
		$value =  $raw_value ;
		break;
	}

/**
* Fire an action when a certain 'type' of field is being saved.
*
* @deprecated 2.4.0 - doesn't allow manipulation of values!
*/
if ( has_action( 'lasoon_update_option_' . sanitize_title( $option['type'] ) ) ) {
	wc_deprecated_function( 'The lasoon_update_option_X action', '2.4.0', 'lasoon_admin_settings_sanitize_option filter' );
	do_action( 'lasoon_update_option_' . sanitize_title( $option['type'] ), $option );
	continue;
}

/**
* Sanitize the value of an option.
*
* @since 2.4.0
*/
$value = apply_filters( 'lasoon_admin_settings_sanitize_option', $value, $option, $raw_value );

/**
* Sanitize the value of an option by option name.
*
* @since 2.4.0
*/
$value = apply_filters( "lasoon_admin_settings_sanitize_option_$option_name", $value, $option, $raw_value );

if ( is_null( $value ) ) {
	continue;
}

// Check if option is an array and handle that differently to single values.
if ( $option_name && $setting_name ) {
	if ( ! isset( $update_options[ $option_name ] ) ) {
		$update_options[ $option_name ] = get_option( $option_name, array() );
	}
	if ( ! is_array( $update_options[ $option_name ] ) ) {
		$update_options[ $option_name ] = array();
	}
	$update_options[ $option_name ][ $setting_name ] = $value;
} else {
	$update_options[ $option_name ] = $value;
}

$autoload_options[ $option_name ] = isset( $option['autoload'] ) ? (bool) $option['autoload'] : true;

/**
* Fire an action before saved.
*
* @deprecated 2.4.0 - doesn't allow manipulation of values!
*/
do_action( 'lasoon_update_option', $option );
}

// Save all options in our array.
foreach ( $update_options as $name => $value ) {
	update_option( $name, $value, $autoload_options[ $name ] ? 'yes' : 'no' );
}

return true;
}

}

endif;