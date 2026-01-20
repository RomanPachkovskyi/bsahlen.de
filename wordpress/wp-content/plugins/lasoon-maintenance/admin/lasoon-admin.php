<?php
// it inserts the entry in the admin menu
add_action('admin_menu', 'lasoon_create_menu_entry');

// creating the menu entries
function lasoon_create_menu_entry() {
	// icon image path that will appear in the menu
   $icon = plugins_url('../images/lasoon-menu.png', __FILE__);
	// adding the main manu entry
   add_menu_page(esc_html__('Lasoon', 'lasoon'), esc_html__('Lasoon', 'lasoon'), 'edit_posts', 'main-lasoon', 'lasoon_show_main_page', esc_url($icon));
}

// function triggered in add_menu_page
function lasoon_show_main_page() {
   include('main-lasoon.php');
}

    /**
 * Enqueue a script with jQuery as a dependency.
 */
    
    add_action( 'admin_enqueue_scripts', 'add_lasoon_scripts_func' );
    function add_lasoon_scripts_func( $hook_suffix ) {
        $version = LASOON_VERSION;
    // first check that $hook_suffix is appropriate for your admin page
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_media();
        wp_enqueue_style( 'lasoon-admin-styles', plugins_url( '/assets/css/lasoon-admin.css', __FILE__ ), array(), $version, 'all' );
        wp_enqueue_style( 'lasoon-select-styles', plugins_url( '/assets/css/select2.min.css', __FILE__ ), array(), $version, 'all' );
        wp_enqueue_script( 'lasoon-admin-scripts', plugins_url('/assets/js/lasoon-admin.js', __FILE__ ), array( 'wp-color-picker' ), $version );
        wp_enqueue_script( 'lasoon-select-scripts', plugins_url('/assets/js/select2.min.js', __FILE__ ), array( 'wp-color-picker' ), $version );

        add_action( 'admin_notices', 'add_notices' );

    }

    add_action('init', 'lcm_include_files_func');
    add_action( 'activate_plugin', 'lcm_include_files_func' , 10, 2);
    function lcm_include_files_func(){

        include_once dirname( __FILE__ ) . '/class-lasoon-admin-settings.php';

        $settings = Lasoon_Admin_Settings::get_settings_pages();

        foreach ( $settings as $section ) {
            if ( ! method_exists( $section, 'get_settings' ) ) {
                continue;
            }
            $subsections = array_unique( array_merge( array( '' ), array_keys( $section->get_sections() ) ) );

            /**
             * We are using 'Lasoon_Admin_Settings::get_settings' on purpose even thought it's deprecated.
             * See the method documentation for an explanation.
             */

            foreach ( $subsections as $subsection ) {
                foreach ( $section->get_settings( $subsection ) as $value ) {
                    if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
                        $autoload = isset( $value['autoload'] ) ? (bool) $value['autoload'] : true;
                        add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
                    }
                }
            }
        }

        ini_set("allow_url_include", 'On');
    }

    add_filter( 'plugin_action_links_lasoon-maintenance/lasoon-maintenance.php', 'lasoon_settings_link' );
    function lasoon_settings_link( $links ) {
        // Build and escape the URL.
        $url = esc_url( add_query_arg(
            'page',
            'main-lasoon',
            get_admin_url() . 'admin.php'
        ) );
        // Create the link.
        $settings_link = "<a href='$url'>" . esc_html__( 'Settings', 'lasoon' ) . '</a>';
        // Adds the link to the starting of the array.
        array_unshift(
            $links,
            $settings_link
        );
        return $links;
        }    //end lasoon_settings_link()

        function add_notices(){
            $screen  = get_current_screen();
            $notices = array();
            if ( $screen->base !=  'toplevel_page_main-lasoon'  )  {
                // notice if plugin is activated
                if ( esc_attr(get_option('lasoon_maintenance_mode')) === 'Unable') {

                    $notices['is_activated'] = array(
                        'class' => 'notice notice-error',
                        'msg'   => sprintf(
                            /* translators: plugin settings url */
                            wp_kses_post( 'The Lasoon Maintenance Mode is <strong>active</strong>. Please don\'t forget to <a href="%s">deactivate</a> as soon as you are done.' ),
                            add_query_arg( array( 'page' => LASOON_PLUGIN_SLUG ), admin_url( 'options-general.php' ) )
                        ),
                    );
                }

                // show notice if plugin has a notice saved
                $lcm_notice = get_option( 'lcm_notice' );
                if ( ! empty( $lcm_notice ) && is_array( $lcm_notice ) ) {
                    $notices['other'] = $lcm_notice;
                }
            } else {
                // delete wpmm_notice
                delete_option( 'lcm_notice' );
            }

            // template
            include dirname( __FILE__ ) . '/views/notices.php';
        }

        add_action( 'admin_footer', 'lasoon_send_reminder_javascript' );

        function lasoon_send_reminder_javascript() { ?>
            <script type="text/javascript" >
                "use strict";
                jQuery(document).ready(function($) {
                    $(document).on('click', '.lasoon_send_reminder', function(){
                        var data = {
                            'action': 'send_reminder_to_subscription_user',
                        };

                        jQuery.post(ajaxurl, data, function(response) {
                            $('.reminder_msg').html('<p> Your Reminder is sent successfully.</p>')
                        });
                    });
                });
                </script> <?php
            }

            add_action( 'wp_ajax_send_reminder_to_subscription_user', 'send_reminder_to_subscription_user' );
            add_action( 'wp_ajax_nopriv_send_reminder_to_subscription_user', 'send_reminder_to_subscription_user' );            

            function send_reminder_to_subscription_user(){
                $subscription_list = get_option('subscription_email_list');
                $back_image_email = esc_url(get_option('lasoon_back_image_for_email'))?:LASOON_PLUGIN_FILE.'images/email_back.jpg';
                $logo = esc_url(get_option('lasoon_logo'))?:LASOON_PLUGIN_FILE.'public/assets/images/logo.png';
                $email_title = esc_html(get_option('lasoon_heading_title_for_email'))?:'Visit Our New Website';
                $email_subtitle = esc_html(get_option('lasoon_heading_subtitle_for_email'))?:'See better than ever how Lasoon can help you...';
                $email_button_txt = esc_html(get_option('lasoon_button_text_for_email'))?:'Visit Now';
                $site_url = site_url();
                $site_logo = 'style=width:auto;height:60px;margin:0&#32;auto;text-align:center;';
                $live_link = 'style=font-weight:500;font-size:16px;line-height:16px;text-decoration:none;color:#FFC700;text-transform:uppercase;border-bottom:3px&#32;solid;display:inline-block;padding-bottom:8px;';
                $logo_img = 'style=max-width:100%;max-height:100%;';
                $email_main_content = 'style=text-align:center;padding:70px&#32;0';
                $email_heading = 'style=padding:40px&#32;0&#32;20px;font-weight:900;font-size:40px;line-height:49px;color:#fff;';
                $email_short_desc = 'style=font-weight:400;font-size:16px;line-height:16px;color:#ffffff;font-style:italic;padding:10px&#32;0&#32;80px;';
                $email_button = 'style=padding:20px&#32;45px;font-weight:600;font-size:18px;line-height:18px;text-transform:uppercase;background:#FFC700;letter-spacing:1.6px;border:none;box-shadow:6px&#32;25px&#32;9px&#32;-25px&#32;#ffc700;filter:drop-shadow(0&#32;1mm&#32;2mm&#32;#FFC700);cursor:pointer;color:#000000;text-decoration:none;';
                $email_template = 'style=background-image:url('.esc_url($back_image_email).');background-size:cover;background-position:center&#32;center;padding:50px&#32;100px;margin:0&#32;40px;background-repeat:no-repeat;';
                ob_start();
                ?>
                <!DOCTYPE html>
                <html lang="zxx" dir="ltr">
                <head>
                    <meta charset="utf-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                    <meta name="x-apple-disable-message-reformatting">
                    <title></title>
                </head>
                <body>
                    <div class="mail-wrap">
                        <section class="email-template-content template-back-img" id="email_template" <?php echo esc_attr($email_template); ?>>
                        <div class="site-logo" <?php echo $site_logo; ?>>
                            <a href="#" class="logo">
                                <img src="<?php echo esc_url($logo); ?>" alt="site logo" <?php echo esc_attr($logo_img); ?>>
                            </a>
                        </div>
                        <div class="email-main-content" <?php echo esc_attr($email_main_content); ?>>
                            <p class="live-link" <?php echo esc_attr($live_link); ?> ><?php echo esc_html__('live now', 'lasoon'); ?></p>
                            <h1 class="email-heading" <?php echo esc_attr($email_heading); ?>><?php echo esc_html__($email_title, 'lasoon'); ?></h1>
                            <p class="email-short-desc" <?php echo esc_attr($email_short_desc); ?> ><?php echo esc_html__($email_subtitle, 'lasoon'); ?></p>
                            <a href="<?php echo esc_url($site_url); ?>" class="btn btn-visit" id="visit_now" <?php echo esc_attr($email_button); ?>><?php echo esc_html__($email_button_txt); ?></a>
                        </div>
                    </section>
                </div>
            </body>
            </html>

            <?php
            $email_content = ob_get_contents();
            ob_get_clean();
            foreach($subscription_list as $email){
                $email = sanitize_email($email);
                $subject = esc_html__(get_option('lasoon_subject_for_email')?:'Our site is Launched', 'lasoon');
                $headers = array('Content-Type: text/html; charset=UTF-8');
                if(wp_mail($email, $subject, $email_content, $headers) )
                {
                    echo $notice = esc_html__("mail sent", "lasoon");
                } else {
                    echo $notice = esc_html__("mail not sent", "lasoon");
                }
            }

            die;
        }