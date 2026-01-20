<?php
/**
 * Admin View: Settings
 *
 * @package Lasoon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$tab_exists  = isset( $tabs[ $current_tab ] ) || has_action( 'lasoon_sections_' . $current_tab ) || has_action( 'lasoon_settings_' . $current_tab ) || has_action( 'lasoon_settings_tabs_' . $current_tab );
$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';
?>
<div class="wrap lasoon">
	<?php do_action( 'lasoon_before_settings_' . $current_tab ); ?>
	<form method="<?php echo esc_attr( apply_filters( 'lasoon_settings_form_method_tab_' . $current_tab, 'post' ), 'lasoon' ); ?>" id="mainlasoonform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper lasoon-nav-tab-wrapper">
			<div class="lasoon_logo_admin">
				<img src="<?php echo esc_url(LASOON_PLUGIN_FILE); ?>images/lasson-logo.png" alt="lasoon logo">
			</div>

			<?php
			foreach ( $tabs as $slug => $label ) {
				echo '<a href="' . esc_url( admin_url( 'admin.php?page=main-lasoon&tab=' . esc_attr( $slug ) ), 'lasoon' ) . '" class="lasoon-nav-tab ' . ( $current_tab === $slug ? 'lasoon-nav-tab-active' : '' ) . '">' . esc_html__( $label, 'lasoon' ) . '</a>';
			}

			do_action( 'lasoon_settings_tabs' );

			?>
		</nav>
		<div class="lasoon-settings-content">
			<h1 class="screen-reader-text"><?php echo esc_html__( $current_tab_label , 'lasoon'); ?></h1>
			
				<?php
					do_action( 'lasoon_sections_' . $current_tab );

					self::show_messages();
					do_action( 'lasoon_settings_' . $current_tab );
					do_action( 'lasoon_settings_tabs_' . $current_tab ); // @deprecated 3.4.0 hook.
				?>
				<p class="submit">
					<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
						<button name="save" class="button-primary lasoon-save-button" type="submit" value="<?php echo esc_attr( 'Save changes', 'lasoon' ); ?>"><?php echo esc_html__( 'Save changes', 'lasoon' ); ?></button>
					<?php endif; ?>
					<?php wp_nonce_field( 'lasoon-settings' ); ?>
				</p>
		</div>
	</form>
	<?php do_action( 'lasoon_after_settings_' . $current_tab ); ?>
</div>