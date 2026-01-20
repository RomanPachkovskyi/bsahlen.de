<?php
/**
 * Notices
 *
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

foreach ( $notices as $key => $notice ) {
	printf( '<div id="message" class="%s" data-key="%s"><p>%s</p></div>', esc_attr( $notice['class'] ), esc_attr( $key ), wp_kses_post( $notice['msg'] ) );
}
