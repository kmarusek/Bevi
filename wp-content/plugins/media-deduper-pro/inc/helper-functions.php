<?php
/**
 * Helper Functions File
 *
 * @package Media_Deduper_Pro
 */

/**
 * Check if we are on a media deduper admin page.
 */
function is_mdd_admin() {
	// if is not wp admin return false
	if ( ! is_admin() ) {
		return false;
	}
	// get the current wp admin screen
	$screen = get_current_screen();

	// switch the current screen id
	// if the screen id matches a mdd id return true
	// default to false if no matches are found
	switch ( $screen->id ) {
		case 'media_page_media-deduper':
		case 'settings_page_mdd_pro':
			return true;
			break;
		default:
			return false;
	}
}

/**
 * Get the basic authentication check output.
 *
 * @param bool $echo Whether to return or echo the output.
 */
function mdd_basic_auth_check_output( $echo = true ) {
	$output = esc_html( ( mdd_basic_auth_check() ) ? __( 'yes', 'media-deduper' ) : __( 'no', 'media-deduper' ) );
	if ( false === $echo ) {
		return $output;
	}

	echo $output; // phpcs:ignore
}

/**
 * Get the basic authentication check status.
 *
 * @return bool
 */
function mdd_basic_auth_check() {
	// Get any existing copy of our transient data
	if ( false === ( $request = get_transient( 'mdd_wp_cron_request' ) ) ) {
		// It wasn't there, so regenerate the data and save the transient
		$url     = site_url( 'wp-cron.php?doing_wp_cron' );
		$request = wp_remote_request( $url );
		set_transient( 'mdd_wp_cron_request', $request, 12 * HOUR_IN_SECONDS );
	}

	if ( is_wp_error( $request ) ) {
		return true;
	}

	if ( isset( $request['headers']['www-authenticate'] ) ) {
		return true;
	}

	if ( 401 === $request['response']['code'] ) {
		return true;
	}

	return false;
}

/**
 * Get the action scheduler test status output
 *
 * @param bool $echo Whether to return or echo the output.
 */
function mdd_as_test_output( $echo = true ) {
	$as_test_action = as_get_scheduled_actions(
		array(
			'hook'   => 'mdd_as_test_action',
			'order'  => 'DESC',
			'status' => ActionScheduler_Store::STATUS_COMPLETE,
		),
		'ids'
	);

	$log_entries = ActionScheduler::logger()->get_logs( $as_test_action[0] );

	$output = '';

	$timezone = new DateTimezone( 'UTC' );

	foreach ( $log_entries as $log_entry ) {
		$date = $log_entry->get_date();
		$date->setTimezone( $timezone );
		$output .= sprintf( "\t<strong> Last Completed %s</strong>: %s<br />", esc_html( $log_entry->get_message() ), esc_html( $date->format( 'Y-m-d H:i:s O' ) ) );
	}

	if ( false === $echo ) {
		return $output;
	}

	echo $output;	// phpcs:ignore
}
