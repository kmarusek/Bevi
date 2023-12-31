<?php
/**
 * The main Media Deduper Pro plugin class.
 *
 * @package Media_Deduper_Pro
 */

register_activation_hook( MDD_PRO_FILE, array( 'Media_Deduper_Pro', 'activate' ) );
register_uninstall_hook( MDD_PRO_FILE, array( 'Media_Deduper_Pro', 'uninstall' ) );

/**
 * The main Media Deduper plugin class.
 */
class Media_Deduper_Pro {

	/**
	 * Plugin version.
	 */
	const VERSION = '1.5.1';

	/**
	 * Special hash value used to mark an attachment if its file can't be found.
	 */
	const NOT_FOUND_HASH = 'not-found';

	/**
	 * Default size value used if an attachment post's file can't be found.
	 */
	const NOT_FOUND_SIZE = 0;

	/**
	 * The ID of the admin screen for this plugin.
	 */
	const ADMIN_SCREEN = 'media_page_media-deduper';

	/**
	 * Plugin Rest endpoint namespace.
	 */
	const REST_NAMESPACE = 'mdd_pro';

	/**
	 * The number of attachments deleted during a 'smart delete' operation.
	 *
	 * @var int Set/incremented by Media_Deduper_Pro::smart_delete_media().
	 */
	protected $smart_deleted_count = 0;

	/**
	 * The number of attachments skipped during a 'smart delete' operation.
	 *
	 * @var int Set/incremented in Media_Deduper_Pro::smart_delete_media().
	 */
	protected $smart_skipped_count = 0;

	/**
	 * When the plugin is activated after being inactive, clear any previously cached transients, and
	 * make sure the `mdd_hash_index` DB index exists.
	 */
	static function activate() {
		static::delete_transients();
		static::db_index( 'add' );
	}

	/**
	 * Main constructor, primarily used for registering hooks.
	 */
	function __construct() {

		// Require the MDD helper functions file.
		require_once MDD_PRO_INCLUDES_DIR . 'helper-functions.php';

		// Class for maintaining compatibility with earlier Media Deduper versions' data.
		require_once MDD_PRO_INCLUDES_DIR . 'class-mdd-compat-manager.php';
		$this->compat_manager = new MDD_Compat_Manager();

		// When the plugin is deactivated, remove the db index.
		register_deactivation_hook( MDD_PRO_FILE, array( $this, 'deactivate' ) );

		// Class for premium plugin activation/deactivation.
		require_once MDD_PRO_INCLUDES_DIR . 'class-mdd-license-manager.php';
		$this->license_manager = new MDD_License_Manager();

		// Class for attachment reference tracking/replacement.
		require_once MDD_PRO_INCLUDES_DIR . 'class-mdd-reference-handler.php';
		$this->reference_handler = new MDD_Reference_Handler();

		// Class for testing async functionality (as used by the indexer).
		require_once MDD_PRO_INCLUDES_DIR . 'class-mdd-async-test.php';
		$this->async_test = new MDD_Async_Test();

		// Class for bulk processing.
		// Set up the bulk process objects and their labels.
		require_once MDD_PRO_INCLUDES_DIR . 'class-mdd-bulk-processor.php';
		$this->indexer = new MDD_Bulk_Processor( 'index', array( $this, 'index_item' ) );
		$this->indexer_labels = array(
			'items-processed' => sprintf(
				// translators: %1$d: Number of attachment posts indexed. %2$d: Total number of attachment posts.
				__( 'Looks like %1$d of %2$d media items have been indexed.', 'media-deduper' ),
				$this->get_count( 'indexed' ),
				$this->get_count()
			),
			'call-to-action' => __( 'Please index all media now.', 'media-deduper' ),
			// translators: %s date of last full index completed
			'comprehensive-info' => __( 'Last full index completed on %s.', 'media-deduper' ),
			'none-comprehensive-info' => __( 'All media have been indexed.', 'media-deduper' ),
			'stopped-heading' => __( 'The last indexer process, which was stopped manually, resulted in %d errors:', 'media-deduper' ),
			'error-heading' => __( 'The last indexer process resulted in %d errors:', 'media-deduper' ),
			'submit' => __( 'Index Media', 'media-deduper' ),
			're-submit' => __( 'Re-Index Media', 'media-deduper' ),
		);
		$this->bulk_delete_unused = new MDD_Bulk_Processor( 'bulk_delete_unused', array( $this, 'bulk_delete_unused_item' ) );
		$this->bulk_delete_unused_labels = array(
			'items-processed' => sprintf(
				// translators: %1$d: Number of attachment posts indexed. %2$d: Total number of attachment posts.
				__( 'Looks like %1$d of %2$d media are unused.', 'media-deduper' ),
				$this->get_count( 'unused' ),
				$this->get_count()
			),
			'call-to-action' => __( 'Consider Bulk deleting unused media.', 'media-deduper' ),
			// translators: %s date of last full index completed
			'comprehensive-info' => __( 'Last full bulk process completed on %s.', 'media-deduper' ),
			'none-comprehensive-info' => __( 'All unsued media has been deleted.', 'media-deduper' ),
			'stopped-heading' => __( 'The last bulk process, which was stopped manually, resulted in %d errors:', 'media-deduper' ),
			'error-heading' => __( 'The last bulk process resulted in %d errors:', 'media-deduper' ),
			'submit' => __( 'Bulk Delete Unused Media', 'media-deduper' ),
			're-submit' => __( 'Bulk Delete Unused Media', 'media-deduper' ),
		);
		$this->smart_bulk_delete = new MDD_Bulk_Processor( 'smart_bulk_delete', array( $this, 'smart_bulk_delete_item' ) );
		$this->smart_bulk_delete_labels = array(
			'items-processed' => sprintf(
				// translators: %1$d: Number of attachment posts indexed. %2$d: Total number of attachment posts.
				__( 'Looks like %1$d of %2$d media items are duplicates.', 'media-deduper' ),
				$this->get_count( 'duplicates' ),
				$this->get_count()
			),
			'call-to-action' => __( 'Consider bulk smart deleting duplicates.', 'media-deduper' ),
			// translators: %s date of last full index completed
			'comprehensive-info' => __( 'Last full bulk smart delete process completed on %s.', 'media-deduper' ),
			'none-comprehensive-info' => __( 'All duplicate media has been deleted.', 'media-deduper' ),
			'stopped-heading' => __( 'The last smart bulk delete process, which was stopped manually, resulted in %d errors:', 'media-deduper' ),
			'error-heading' => __( 'The last smart bulk delete process resulted in %d errors:', 'media-deduper' ),
			'submit' => __( 'Smart Bulk Delete Duplicate Media', 'media-deduper' ),
			're-submit' => __( 'Smart Bulk Delete Duplicate Media', 'media-deduper' ),
		);

		// Class for handling outputting the duplicates.
		require_once MDD_PRO_INCLUDES_DIR . 'class-mdd-media-list-table.php';

		// Use an existing capabilty to check for privileges. manage_options may not be ideal, but gotta use something...
		$this->capability = apply_filters( 'media_deduper_cap', 'manage_options' );

		$this->load_dependencies();

		add_action( 'cshp_settings_page_after_section_mdd_pro', array( $this, 'debug_info_section' ) );

		add_action( 'rest_api_init',              array( $this, 'rest_endpoints' ) );
		add_action( 'wp_ajax_mdd_index_status',   array( $this, 'ajax_index_status' ) );
		add_action( 'wp_ajax_mdd_index_stop',     array( $this, 'ajax_index_stop' ) );

		add_action( 'wp_ajax_mdd_async_test',     array( $this, 'ajax_async_test' ) );

		add_action( 'admin_menu',                 array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts',      array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_init',                 array( $this, 'admin_init' ) );
		add_action( 'admin_notices',              array( $this, 'admin_notices' ), 11 );

		// When add_metadata() or update_metadata() is called to set a new or
		// existing attachment's _wp_attached_file value, (re)calculate the
		// attachment's file hash.
		add_action( 'added_post_meta',            array( $this, 'after_add_file_meta' ), 10, 3 );
		add_action( 'update_post_metadata',       array( $this, 'before_update_file_meta' ), 10, 5 );

		// When an attachment is deleted, invalidate the cached list of duplicate
		// IDs, because there may be another attachment that would previously have
		// been considered a duplicate, but is now unique.
		add_action( 'delete_attachment',          array( 'Media_Deduper_Pro', 'delete_transients' ) );

		// When references are tracked in a post, invalidate the cached list of indexed post IDs.
		add_action( 'mdd_tracked_post_props',     array( 'Media_Deduper_Pro', 'delete_transients' ) );
		add_action( 'mdd_tracked_post_meta',      array( 'Media_Deduper_Pro', 'delete_transients' ) );
		add_action( 'mdd_tracked_deleted_post',   array( 'Media_Deduper_Pro', 'delete_transients' ) );

		// Check if actions that require re-indexing have changed.
		add_action( 'updated_option', array( $this, 'updated_option' ), 10, 3 );
		add_action( 'added_option', array( $this, 'added_option' ), 10, 2 );

		// If the user tries to upload a file whose hash matches an existing file,
		// stop them.
		add_filter( 'wp_handle_upload_prefilter', array( $this, 'block_duplicate_uploads' ) );

		// Set removable query args (used for displaying messages to the user).
		add_filter( 'removable_query_args',       array( $this, 'removable_query_args' ) );

		// Column handlers.
		add_filter( 'manage_upload_columns',          array( $this, 'media_columns' ) );
		add_filter( 'manage_upload_sortable_columns', array( $this, 'media_sortable_columns' ) );
		add_filter( 'manage_media_custom_column',     array( $this, 'media_custom_column' ), 10, 2 );

		// apply filters and actions that need to run early in the WP process.
		add_filter( 'set-screen-option',                       array( $this, 'save_screen_options' ), 11, 3 );
		add_filter( 'set_screen_option_mdd_per_page',          array( $this, 'save_screen_options' ), 11, 3 );

		add_filter( 'views_upload', array( $this, 'media_views' ) );

		// Row actions (view/edit/delete, etc.) for the duplicates list table.
		add_filter( 'media_row_actions',          array( $this, 'media_row_actions' ), 10, 2 );

		// Allow admin notices to be hidden for the current user via AJAX.
		add_action( 'wp_ajax_mdd_dismiss_notice', array( $this, 'ajax_dismiss_notice' ) );

		// run a daily test to ensure action scheduler is running.
		add_action( 'mdd_as_test_action', array( $this, 'mdd_as_test_action' ), 10 );
		if ( false === as_has_scheduled_action( 'mdd_as_test_action' ) ) {
			as_schedule_recurring_action( strtotime( 'tomorrow' ), DAY_IN_SECONDS, 'mdd_as_test_action' );
		}

		// Query filters (for adding sorting options in wp-admin).
		if ( is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}
	}

	/**
	 * Load Dependencies Step.
	 */
	private function load_dependencies() {
		require_once( MDD_PRO_INCLUDES_DIR . 'class-mdd-admin-notice.php' );
		$this->require_all( MDD_PRO_INCLUDES_DIR . 'settings' );

		$this->build_menu_settings();
	}

	/**
	 * Build the admin page.
	 */
	private function build_menu_settings() {
		require_once( MDD_PRO_PATH . 'admin/menu-settings.php' );
	}

	/**
	 * Add Debug info Section to the admin page.
	 */
	public function debug_info_section() {
		require_once( MDD_PRO_PATH . 'admin/debug-info.php' );
	}

	/**
	 * Get the currently active tab.
	 */
	static public function active_tab() {
		return ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'duplicates' );
	}

	/**
	 * Enqueue the media js file from core. Also enqueue our own assets.
	 */
	public function enqueue_scripts() {

		// only enqueue this style/scripts on mdd admin screens.
		if ( is_mdd_admin() ) {
			// Enqueue the main media JS + our own JS on the Manage Duplicates screen.
			wp_enqueue_media();
			wp_enqueue_script( 'media-grid' );
			wp_enqueue_script( 'media' );
			wp_enqueue_script( 'media-deduper-js', plugins_url( 'media-deduper.js', MDD_PRO_FILE ), array( 'underscore' ), static::VERSION, true );

			// Add localization strings. If this is the indexer tab, additional data
			// will be added later.
			$duplicate_count = ( 0 !== absint( $this->get_duplicate_ids()[0] ) ) ? count( $this->get_duplicate_ids() ) : 0;
			$unused_count = ( isset( $this->get_attachment_ids( false, true )[0] ) && 0 !== absint( $this->get_attachment_ids( false, true )[0] ) ) ? count( $this->get_attachment_ids( false, true ) ) : 0;

			// Set up JS localization strings
			// Bulk process related localize strings.
			wp_localize_script(
				'media-deduper-js', 'mdd_l10n', array(
					'warning_delete'        => __( "Warning: This will modify your files and content!!!!!!! (Lots of exclamation points because it’s seriously that big of a deal.)\n\nWe strongly recommend that you BACK UP YOUR UPLOADS AND DATABASE before performing this operation.\n\nClick 'Cancel' to stop, 'OK' to delete.", 'media-deduper' ),
					'stopping'              => esc_html__( 'Stopping...', 'media-deduper' ),
					'stopped'               => esc_html__( 'Stopped', 'media-deduper' ),
					'bulk_process_errors'   => __( 'Errors:', 'media-deduper' ),
					'index_complete' => array(
						'issues' => '<p>'
							. esc_html__( 'Indexing complete;', 'media-deduper' )
							. ' <strong>'
							// translators: %s: The number of files that we failed to index.
							. esc_html( sprintf( __( '%s files could not be indexed.', 'media-deduper' ), '{NUM}' ) )
							. sprintf( _n( ' Found %s duplicate.', ' Found %s duplicates.', $duplicate_count, 'media-deduper' ), number_format_i18n( $duplicate_count ) )
							. ' <a href=\'' . esc_url( admin_url( 'upload.php?page=media-deduper' ) ) . '\'>'
							. esc_html__( 'Manage duplicates now.', 'media-deduper' )
							. '</a></strong></p>',
						'perfect' => '<p>' . esc_html__( 'Indexing complete;', 'media-deduper' ) . ' <strong>' . esc_html__( 'All media and posts successfully indexed.', 'media-deduper' ) . sprintf( _n( ' Found %s duplicate', ' Found %s duplicates.', $duplicate_count, 'media-deduper' ), number_format_i18n( $duplicate_count ) ) . '</strong></p>',
						'aborted' => '<p>' . esc_html__( 'Indexing aborted; only some items indexed.', 'media-deduper' ) . '</p>',
					),
					'bulk_delete_unused_complete' => array(
						'issues' => '<p>'
							. esc_html__( 'Bulk Delete complete;', 'media-deduper' )
							. ' <strong>'
							// translators: %s: The number of files that we failed to index.
							. esc_html( sprintf( __( '%s files could not be deleted.', 'media-deduper' ), '{NUM}' ) )
							. sprintf( _n( ' Found %s unused media file.', ' Found %s unused media files.', $unused_count, 'media-deduper' ), number_format_i18n( $unused_count ) )
							. ' <a href=\'' . esc_url( admin_url( 'upload.php?page=media-deduper' ) ) . '\'>'
							. esc_html__( 'Manage duplicates now.', 'media-deduper' )
							. '</a></strong></p>',
						'perfect' => '<p>' . esc_html__( 'Bulk Delete complete;', 'media-deduper' ) . ' <strong>' . esc_html__( 'Unused files successfully deleted.', 'media-deduper' ) . sprintf( _n( ' Found %s unused media file.', ' Found %s unused media files.', $unused_count, 'media-deduper' ), number_format_i18n( $unused_count ) ) . '</strong></p>',
						'aborted' => '<p>' . esc_html__( 'Bulk Delete aborted; only some items deleted.', 'media-deduper' ) . '</p>',
					),
					'smart_bulk_delete_complete' => array(
						'issues' => '<p>'
							. esc_html__( 'Smart Bulk Delete complete;', 'media-deduper' )
							. ' <strong>'
							// translators: %s: The number of files that we failed to index.
							. esc_html( sprintf( __( '%s files could not be smart deleted.', 'media-deduper' ), '{NUM}' ) )
							. sprintf( _n( ' Found %s duplicate.', ' Found %s duplicates.', $duplicate_count, 'media-deduper' ), number_format_i18n( $duplicate_count ) )
							. ' <a href=\'' . esc_url( admin_url( 'upload.php?page=media-deduper' ) ) . '\'>'
							. esc_html__( 'Manage duplicates now.', 'media-deduper' )
							. '</a></strong></p>',
						'perfect' => '<p>' . esc_html__( 'Smart Bulk Delete complete;', 'media-deduper' ) . ' <strong>' . esc_html__( 'All media successfully Smart Deleted.', 'media-deduper' ) . sprintf( _n( ' Found %s duplicate', ' Found %s duplicates.', $duplicate_count, 'media-deduper' ), number_format_i18n( $duplicate_count ) ) . '</strong></p>',
						'aborted' => '<p>' . esc_html__( 'Smart Bulk Delete aborted; only some items smart deleted.', 'media-deduper' ) . '</p>',
					),
					'async_test_running' => '<p>' . __( 'Testing asynchronous task execution...', 'media-deduper' ) . '</p>',
					'async_test_successful' => '<p>' . __( 'Asynchronous task execution is working.', 'media-deduper' ) . '</p>',
					'async_test_failed' => '<p>' . __( 'Asynchronous task execution is not functioning correctly, which may cause problems with the Media Deduper indexing process.', 'media-deduper' ) . '</p>',
					'selected_text' => __( 'We have selected it for you.', 'media-deduper' ),
					'rest_api' => array(
						'root'          => esc_url_raw( rest_url() ),
						'nonce'         => wp_create_nonce( 'wp_rest' ),
						'versionString' => static::REST_NAMESPACE . '/v2/',
					),
				)
			);

			// Enqueue our admin CSS on both the Manage Duplicates screen and the main
			// This used to be only enqueue on the MDD related pages but newer needs require a more global enqueue
			wp_enqueue_style( 'media-deduper', plugins_url( 'media-deduper.css', MDD_PRO_FILE ), array(), static::VERSION );
		}//end if

		// Enqueue script to reformat uploader error messages.
		// This one's enqueued unconditionally, since the media frame could be used just about anywhere.
		wp_enqueue_script( 'media-deduper-uploader-js', plugins_url( 'media-deduper-uploader.js', MDD_PRO_FILE ), array(), static::VERSION );
	}

	/**
	 * Admin init callback.
	 */
	function admin_init() {

		if ( isset( $_GET['mdd_cbur_nonce'] ) && wp_verify_nonce( $_GET['mdd_cbur_nonce'], 'mdd_clear_basic_auth_request' ) ) {
			delete_transient( 'mdd_wp_cron_request' );
		}

		if ( (bool) get_option( 'mdd_pro_reindex_option_changed' ) ) {
			new MDD_Admin_Notice(
				sprintf(
					// translators: %s: Link URL.
					__( 'A setting has changed that requires the <strong><a href="%s">regeneration of the media index</a></strong>.', 'media-deduper' ),
					admin_url( 'upload.php?page=media-deduper&tab=index' )
				), 'notice notice-warning'
			);
		}

		if ( (bool) mdd_basic_auth_check() ) {
			new MDD_Admin_Notice(
				sprintf(
					// translators: %s: Link URL.
					__( 'It looks like your site is password-protected, which may interfere with Media Deduper Pro’s ability to index your media and content. <strong><a href="%1$s">Check our documentation</a></strong> for more info and suggested troubleshooting. <a href="%2$s" class="button button-primary">Check again</a>', 'media-deduper' ),
					'https://support.cornershopcreative.com/support/solutions/articles/43000655565-media-deduper-pro-is-stuck-on-indexing-at-0-',
					wp_nonce_url( admin_url( 'options-general.php?page=mdd_pro' ), 'mdd_clear_basic_auth_request', 'mdd_cbur_nonce' )
				), 'notice notice-warning is-dismissible'
			);
		}

		// Check the license key and store its current status. In order to display the expiration message.
		$this->license_manager->check_license();
	}

	/**
	 * Remind people they need to do things.
	 */
	public function admin_notices() {

		// If the current user isn't allowed to view the MDD admin screen, bail. None of the messages
		// we'd show here are relevant to users who can't rebuild the index, etc.
		if ( ! current_user_can( $this->capability ) ) {
			return;
		}

		$screen = get_current_screen();
		$html = '';

		if ( ! get_option( 'mdd-pro-activated', false ) && $this->get_count( 'indexed' ) < $this->get_count() ) {

			// On initial plugin activation, point to the indexing page.
			add_option( 'mdd-pro-activated', true, '', 'no' );
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf(
				// translators: %s: Link URL.
				__( 'In order to manage duplicate media you must first <strong><a href="%s">generate the media index</a></strong>.', 'media-deduper' ),
				admin_url( 'upload.php?page=media-deduper&tab=index' )
			);
			$html .= '</p></div>';

		} elseif ( 'upload' === $screen->base && $this->get_count( 'indexed' ) < $this->get_count() ) {

			// Otherwise, complain about incomplete indexing if necessary.
			$html = '<div class="error notice is-dismissible"><p>';
			$html .= sprintf(
				// translators: %s: Link URL.
				__( 'Media duplication index is not comprehensive, please <strong><a href="%s">update the index now</a></strong>.', 'media-deduper' ),
				admin_url( 'upload.php?page=media-deduper&tab=index' )
			);
			$html .= '</p></div>';

		} elseif ( 'dashboard' === $screen->base || static::ADMIN_SCREEN === $screen->base ) {

			// On the dashboard or the Manage Duplicates screen (but NOT the License tab), if there's
			// either no license key stored, or the stored license key isn't valid (i.e. expired or just
			// not real), ask the user to enter a valid one.
			if ( 'dashboard' === $screen->base || ! isset( $_GET['tab'] ) || 'license' !== $_GET['tab'] ) {
				// WPCS: CSRF ok.
				if ( ! $this->license_manager->has_license_key() ) {

					$html = '<div class="error notice is-dismissible"><p>';
					$html .= sprintf(
						// translators: %s: Link URL.
						__( 'Thank you for using Media Deduper Pro! Please <a href="%s">enter your license key</a> so you can receive updates when we release them.', 'media-deduper' ),
						admin_url( 'upload.php?page=media-deduper&tab=license' )
					);
					$html .= '</p></div>';

				} elseif ( ! $this->license_manager->is_license_valid() ) {

					$html = '<div class="error notice is-dismissible"><p>';
					$html .= sprintf(
						// translators: %s: Link URL.
						__( 'The license key you have entered for Media Deduper Pro is not valid. Until you <a href="%s">enter a valid license key</a>, you will not be able to receive updates to the plugin.', 'media-deduper' ),
						admin_url( 'upload.php?page=media-deduper&tab=license' )
					);
					$html .= '</p></div>';

				}//end if
			}//end if

			// On the Manage Duplicates page, if a Delete or Smart Delete operation has just been
			// completed, show feedback.
			if ( static::ADMIN_SCREEN === $screen->base && isset( $_GET['smartdeleted'] ) ) {

				// The 'smartdelete' action has been performed. $_GET['smartdelete'] is
				// expected to be a comma-separated pair of values reflecting the number
				// of attachments deleted and the number of attachments that weren't
				// deleted (which happens if all other copies of an image have already
				// been deleted).
				list( $deleted, $skipped ) = array_map( 'absint', explode( ',', $_GET['smartdeleted'] ) );
				// Only output a message if at least one attachment was either deleted
				// or skipped.
				if ( $deleted || $skipped ) {
					$html = '<div class="updated notice is-dismissible"><p>';
					// translators: %1$d: Number of items deleted. %2$d: Number of items skipped.
					$html .= sprintf( __( 'Deleted %1$d items and skipped %2$d items.', 'media-deduper' ), $deleted, $skipped );
					$html .= '</p></div>';
				}
				// Remove the 'smartdeleted' query arg from the REQUEST_URI, since it's
				// served its purpose now and we don't want it weaseling its way into
				// redirect URLs or the like.
				$_SERVER['REQUEST_URI'] = remove_query_arg( 'smartdeleted', $_SERVER['REQUEST_URI'] );

			} elseif ( isset( $_GET['deleted'] ) ) {

				// The 'delete' action has been performed. $_GET['deleted'] is expected
				// to reflect the number of attachments deleted.
				// Only output a message if at least one attachment was deleted.
				$deleted = absint( $_GET['deleted'] );
				if ( $deleted ) {
					// Show a simpler message if only one file was deleted (based on
					// wp-admin/upload.php).
					if ( 1 === $deleted ) {
						$message = __( 'Media file permanently deleted.', 'media-deduper' );
					} else {
						/* translators: %s: number of media files */
						$message = _n( '%s media file permanently deleted.', '%s media files permanently deleted.', $deleted, 'media-deduper' );
					}
					$html = '<div class="updated notice is-dismissible"><p>';
					$html .= sprintf( $message, number_format_i18n( $deleted ) );
					$html .= '</p></div>';
				}
				// Remove the 'deleted' query arg from REQUEST_URI.
				$_SERVER['REQUEST_URI'] = remove_query_arg( 'deleted', $_SERVER['REQUEST_URI'] );

			} //end if
		} //end if

		// phpcs:ignore Squiz.Commenting.PostStatementComment.Found
		echo $html; // WPCS: XSS ok.
	}

	/**
	 * Adds/removes DB index on meta_value to facilitate performance in finding dupes.
	 *
	 * @param string $task 'add' to add the index, any other value to remove it.
	 */
	static function db_index( $task = 'add' ) {

		global $wpdb;
		if ( 'add' === $task ) {
			$sql = "CREATE INDEX `mdd_hash_index` ON $wpdb->postmeta ( meta_value(32) );";
		} else {
			$sql = "DROP INDEX `mdd_hash_index` ON $wpdb->postmeta;";
		}

		$wpdb->query( $sql );

	}

	/**
	 * On deactivation, get rid of our index.
	 */
	public function deactivate() {

		global $wpdb;

		// Kill our index.
		static::db_index( 'remove' );
	}

	/**
	 * On uninstall, get rid of ALL junk.
	 */
	static function uninstall() {
		global $wpdb;

		// Kill all meta generated by MDD. It's annoying to re-generate the index, but we don't want to
		// pollute the DB.
		$wpdb->delete(
			$wpdb->postmeta, array(
				'meta_key' => 'mdd_hash',
			)
		);
		$wpdb->delete(
			$wpdb->postmeta, array(
				'meta_key' => 'mdd_size',
			)
		);
		$wpdb->delete(
			$wpdb->postmeta, array(
				'meta_key' => '_mdd_references',
			)
		);
		$wpdb->delete(
			$wpdb->postmeta, array(
				'meta_key' => '_mdd_referenced_by',
			)
		);
		$wpdb->delete(
			$wpdb->postmeta, array(
				'meta_key' => '_mdd_referenced_by_count',
			)
		);

		// delete transients
		delete_transient( 'mdd_license_data' );

		// Kill our mysql table index.
		static::db_index( 'remove' );

		// Deactivate the license.
		// We need to require and instantiate the license manager since the uninstall hook is register outside the main class.
		require_once( MDD_PRO_INCLUDES_DIR . 'class-mdd-license-manager.php' );
		$license_manager = new MDD_License_Manager();
		$license_manager->deactivate_license();

		// Remove the option indicating activation.
		delete_option( 'mdd-pro-activated' );
	}

	/**
	 * Handle the updated_option callback.
	 *
	 * @param string $option    The option name.
	 * @param mixed  $old_value The options old value.
	 * @param mixed  $new_value The options new value.
	 */
	function updated_option( $option, $old_value, $new_value ) {
		$this->mdd_pro_maybe_reindex_option_changed( $option, $old_value, $new_value );
	}

	/**
	 * Hanlde the added_option callback.
	 *
	 * @param string $option    The option name.
	 * @param mixed  $new_value The options new value.
	 */
	function added_option( $option, $new_value ) {
		$this->mdd_pro_maybe_reindex_option_changed( $option, '', $new_value );
	}

	/**
	 * Maybe Turn on the mdd_pro_reindex_option_changed option
	 * to flag that reindexing is required when certain options change.
	 *
	 * @param string $option    The option name.
	 * @param mixed  $old_value The options old value.
	 * @param mixed  $new_value The options new value.
	 */
	function mdd_pro_maybe_reindex_option_changed( $option, $old_value, $new_value ) {
		$mdd_pro_reindex_options = array( 'mdd_pro_general_section_run_partial_hashes' );
		if ( in_array( $option, $mdd_pro_reindex_options, true ) && $new_value !== $old_value ) {
			update_option( 'mdd_pro_reindex_option_changed', 1 );
		}
	}

	/**
	 * Prevents duplicates from being uploaded.
	 *
	 * @param array $file An array of data for a single file, as passed to
	 *                    _wp_handle_upload().
	 */
	function block_duplicate_uploads( $file ) {

		// Bail if we are uploading a plugin no need to block.
		if ( isset( $_REQUEST['action'] ) && 'upload-plugin' === $_REQUEST['action'] ) {
			return $file;
		}

		// Bail, if User has set the setting to disable blocking duplicate uploads.
		if ( (bool) get_option( 'mdd_pro_general_section_disable_block_duplicate_uploads' ) ) {
			return $file;
		}

		global $wpdb;

		$upload_hash = $this->calculate_hash( $file['tmp_name'] );

		// Does our hash match?
		$sql = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta m JOIN $wpdb->posts p ON p.ID = m.post_id WHERE m.meta_key = 'mdd_hash' AND m.meta_value = %s AND p.post_status != 'trash' LIMIT 1;", $upload_hash );
		$matches = $wpdb->get_var( $sql );
		if ( $matches ) {
			$screen = wp_get_referer();
			// Check to see if we are comming from a post edit or new screen.
			if ( strpos( $screen, 'media-new.php' ) || strpos( $screen, 'upload.php' ) || empty( $screen ) ) {
				$file['error'] = sprintf(
					// translators: %s: The title of the preexisting attachment post.
					__( 'It appears this file is already present in your media library: %s', 'media-deduper' ),
					'#' . $matches . ' (' . get_the_title( $matches ) . ') [' . esc_url( get_edit_post_link( $matches ) ) . ']'
				);
			} else {
				$file['error'] = sprintf(
					// translators: %s: The title of the preexisting attachment post.
					__( 'It appears this file is already present in your media library: %s. We have selected it for you.', 'media-deduper' ),
					'#' . $matches . ' (' . get_the_title( $matches ) . ') [' . esc_url( get_edit_post_link( $matches ) ) . ']'
				);
			}
		}
		return $file;
	}

	/**
	 * When add_post_meta() is called to set an attachment post's initial
	 * _wp_attached_file meta value, calculate the attachment's hash.
	 *
	 * @param int    $meta_id    The ID of the meta value in the postmeta table.
	 *                           Passed in by update_post_meta(), ignored here.
	 * @param int    $post_id    The ID of the post whose meta value has changed.
	 * @param string $meta_key   The meta key whose value has changed.
	 */
	function after_add_file_meta( $meta_id, $post_id, $meta_key ) {

		// If the meta key that was updated isn't _wp_attached_file, bail.
		if ( '_wp_attached_file' !== $meta_key ) {
			return;
		}

		// If this isn't an attachment post, bail.
		if ( 'attachment' !== get_post_field( 'post_type', $post_id, 'raw' ) ) {
			return;
		}

		// Calculate and save the file hash.
		$this->calc_media_meta( $post_id );
	}

	/**
	 * When update_post_meta() is called to set an attachment post's
	 * _wp_attached_file meta value, recalculate the attachment's hash.
	 *
	 * Note: the Enable Media Replace plugin uses a direct db query to set
	 * _wp_attached_file before calling update_attached_file(), so when a file is
	 * changed using EMR, the "new" meta value passed here may be the same as the
	 * old one, and updated_post_meta won't fire because the values are the same.
	 * That's why this function hooks into update_post_metadata, which _always_
	 * fires, instead of updated_post_meta.
	 *
	 * If the new value for the meta key is the same as the old value, this
	 * function will recalculate the attachment hash immediately; if the new value
	 * is different from the old one, this function will attach another hook that
	 * will recalculate the hash _after_ the new meta value has been saved.
	 *
	 * @uses Media_Deduper_Pro::after_update_file_meta()
	 *
	 * @param null|bool $check      Whether to allow updating metadata. Passed in
	 *                              by the update_post_metadata hook, but ignored
	 *                              here -- we don't want to change whether meta
	 *                              is saved, we just want to know if it changes.
	 * @param int       $post_id    Object ID.
	 * @param string    $meta_key   Meta key.
	 * @param mixed     $meta_value Meta value. Must be serializable if non-scalar.
	 * @param mixed     $prev_value Optional. If specified, only update existing
	 *                              metadata entries with the specified value.
	 *                              Otherwise, update all entries.
	 */
	function before_update_file_meta( $check, $post_id, $meta_key, $meta_value, $prev_value ) {

		// If the meta key that was updated isn't _wp_attached_file, bail.
		if ( '_wp_attached_file' !== $meta_key ) {
			return $check;
		}

		// If this isn't an attachment post, bail.
		if ( 'attachment' !== get_post_field( 'post_type', $post_id, 'raw' ) ) {
			return $check;
		}

		// Compare existing value to new value. See update_metadata() in
		// wp-includes/meta.php. If the old value and the new value are the same,
		// then the updated_post_meta action won't fire. The Enable Media Replace
		// plugin might have changed the actual contents of the file, though, even
		// if the filename/path hasn't changed, so now is our chance to update the
		// image hash and size.
		if ( empty( $prev_value ) ) {
			$old_value = get_post_meta( $post_id, $meta_key );
			if ( 1 === count( $old_value ) ) {
				if ( $old_value[0] === $meta_value ) {
					// Recalculate and save the file hash.
					$this->calc_media_meta( $post_id );
					// Leave $check as is to avoid affecting whether or not meta is saved.
					return $check;
				}
			}
		}

		// If the old and new meta values are NOT identical, wait until the metadata
		// is actually saved, and _then_ recalculate the hash.
		add_action( 'updated_post_meta', array( $this, 'after_update_file_meta' ), 10, 3 );

		// Leave $check as is to avoid affecting whether or not meta is saved.
		return $check;
	}

	/**
	 * Calculate the hash for a new attachment post or one whose attached file has
	 * changed.
	 *
	 * @param int    $meta_id    The ID of the meta value in the postmeta table.
	 *                           Passed in by update_post_meta(), ignored here.
	 * @param int    $post_id    The ID of the post whose meta value has changed.
	 * @param string $meta_key   The meta key whose value has changed.
	 */
	function after_update_file_meta( $meta_id, $post_id, $meta_key ) {

		// If the meta key that was updated isn't _wp_attached_file, bail.
		if ( '_wp_attached_file' !== $meta_key ) {
			return;
		}

		// If this isn't an attachment post, bail.
		if ( 'attachment' !== get_post_field( 'post_type', $post_id, 'raw' ) ) {
			return;
		}

		// Calculate the hash for this attachment.
		$this->calc_media_meta( $post_id );

		// Unhook this function from update_post_meta, so it doesn't keep firing for
		// future metadata changes. $this->before_update_meta() will add this
		// function back as needed.
		remove_action( 'updated_post_meta', array( $this, 'after_update_file_meta' ), 10 );
	}

	/**
	 * Calculate the hash for a just-uploaded file.
	 *
	 * @param int $post_id The ID of the attachment post to calculate meta for.
	 * @return array {
	 *     @type bool   $success Whether the hash & size could be calculated correctly.
	 *     @type string $message Human-readable info about what happened.
	 * }
	 */
	function calc_media_meta( $post_id ) {
		$mediafile = get_attached_file( $post_id );

		// If the file doesn't exist, save special "not found" hash + size.
		if ( false === $mediafile || ! file_exists( $mediafile ) ) {
			$this->save_media_meta( $post_id, self::NOT_FOUND_HASH );
			$this->save_media_meta( $post_id, self::NOT_FOUND_SIZE, 'mdd_size' );

			// Delete cached counts.
			static::delete_transients();

			switch ( get_post_status( $post_id ) ) {
				case 'trash':
				case false:
					// Return an error message for logging.
					return new WP_Error(
						'mdd_file_not_found',
						sprintf(
							// translators: %s: Attachment title (links to the Edit Attachment screen).
							__( 'File for trashed attachment %s could not be found.', 'media-deduper' ),
							esc_html( get_the_title( $post_id ) )
						)
					);
					break;
				default:
					// Return an error message for logging.
					return new WP_Error(
						'mdd_file_not_found',
						sprintf(
							// translators: %s: Attachment title (links to the Edit Attachment screen).
							__( 'File for attachment %s could not be found.', 'media-deduper' ),
							'<a href="' . esc_url( get_edit_post_link( $post_id ) ) . '">' . esc_html( get_the_title( $post_id ) ) . '</a>'
						)
					);
			}//end switch
		}//end if

		// Calculate and save hash and size.
		$hash = $this->calculate_hash( $mediafile );
		$size = $this->calculate_size( $mediafile );
		$this->save_media_meta( $post_id, $hash );
		$this->save_media_meta( $post_id, $size, 'mdd_size' );
		// Set a default _mdd_referenced_by_count to 0 when media is added so that they can be ordered correctly by used in column.
		$this->save_media_meta( $post_id, 0, '_mdd_referenced_by_count' );

		// Delete transients, most importantly the attachment count (but duplicate
		// IDs and shared file IDs may have been affected too, if this post was
		// copied meta-value-for-meta-value from another post).
		static::delete_transients();

		// If hash and size were saved, return TRUE to indicate success.
		return true;
	}

	/**
	 * Detect and store attachment references in a post.
	 *
	 * @param int $post_id The ID of the post to track references for.
	 * @return array {
	 *     @type bool   $success Whether the hash & size could be calculated correctly.
	 *     @type string $message Human-readable info about what happened.
	 * }
	 */
	function track_media_refs( $post_id ) {
		$post = get_post( $post_id );

		// If $post doesn't exist, throw an error.
		if ( ! $post ) {
			return new WP_Error(
				'mdd_post_not_found',
				sprintf(
					// translators: %d: Post ID.
					__( 'Post %d could not be found.', 'media-deduper' ),
					$post_id
				)
			);
		}

		$this->reference_handler->track_post( $post_id );

		// Delete transients, most importantly the duplicate ID list + count of
		// indexed attachments.
		static::delete_transients();

		return true;
	}

	/**
	 * Check of any bulk processes are running
	 *
	 * @return bool
	 */
	function is_bulk_processing() {
		$is_bulk_processing = false;
		if ( $this->indexer->is_processing() || $this->bulk_delete_unused->is_processing() || $this->smart_bulk_delete->is_processing() ) {
			$is_bulk_processing = true;
		}

		return $is_bulk_processing;
	}

	/**
	 * Register the rest Endpoints.
	 */
	public function rest_endpoints() {
		$bulk_processes = array( 'index', 'bulk_delete_unused', 'smart_bulk_delete' );

		foreach ( $bulk_processes as $bulk_process ) {
			// Processing Endpoint
			register_rest_route(
				static::REST_NAMESPACE . '/v2',
				"/$bulk_process/",
				array(
					'methods' => 'POST',
					'callback' => array( $this, "rest_$bulk_process" ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Status Endpoint
			register_rest_route(
				static::REST_NAMESPACE . '/v2',
				"/{$bulk_process}_status/",
				array(
					'methods' => 'GET',
					'callback' => array( $this, "rest_{$bulk_process}_status" ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Stop Endpoint
			register_rest_route(
				static::REST_NAMESPACE . '/v2',
				"/{$bulk_process}_stop/",
				array(
					'methods' => 'POST',
					'callback' => array( $this, "rest_{$bulk_process}_stop" ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);

			// Test Endpoint
			register_rest_route(
				static::REST_NAMESPACE . '/v2',
				"/{$bulk_process}_test/",
				array(
					'methods' => 'POST',
					'callback' => array( $this, "rest_{$bulk_process}_test" ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				)
			);
		}//end foreach
	}

	/**
	 * Rest index endpoint callback.
	 */
	public function rest_index( $request ) {
		// If the indexer isn't running...
		if ( ! $this->is_bulk_processing() ) {

			// Check whether _all_ posts should be indexed ($clean === true), or only un-indexed ones.
			$clean = ( is_bool( $request['clean'] ) && ! empty( $request['clean'] ) );

			// Get unhashed attachment IDs.
			$attachments = $this->get_attachment_ids( ! $clean );
			// Get untracked post IDs.
			$posts = $this->get_post_ids();
			// Get total number of items to process.
			$total_count = count( $attachments ) + count( $posts );

			if ( $total_count < 1 ) {

				if ( $clean ) {
					return new WP_Error(
						'no_posts',
						__( 'There are no indexable posts or attachments on this site! As you begin adding content, Media Deduper Pro will index the new content in the background.', 'media-deduper' ),
						array(
							'status' => 200,
						)
					);
				}

				return;
			}

			// Kick off the indexer process.
			$this->indexer->process( array_merge( $attachments, $posts ) );
		} elseif ( ! $this->indexer->is_processing() ) {
			return new WP_Error(
				'bulk_process_already_running',
				__( 'There is another Bulk Process already running, please wait until that process is complete or stop it!', 'media-deduper' ),
				array(
					'status' => 200,
				)
			);
		}//end if

		return new WP_REST_Response( __( 'Index Started.', 'media-deduper' ), 200 );
	}

	/**
	 * Rest index status endpoint callback.
	 */
	public function rest_index_status( $request ) {
		// Get the stored status data.
		$status = $this->indexer->get_status();

		$pending_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => 'mdd_index_batch',
			), 'ids'
		);
		$pending_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => 'mdd_index_item',
			), 'ids'
		);

		$progress_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => 'mdd_index_batch',
			), 'ids'
		);
		$progress_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => 'mdd_index_item',
			), 'ids'
		);

		// there are pending actions we are processing, else lets complete and pass the complete status.
		if ( ! empty( $pending_batches ) || ! empty( $pending_items ) || ! empty( $progress_batches ) || ! empty( $progress_items ) ) {
			$status['state'] = 'processing';
		}

		return new WP_REST_Response( $status, 200 );
	}

	/**
	 * Rest index stop endpoint callback.
	 */
	public function rest_index_stop( $request ) {
		$status = $this->indexer->stop()->get_status();

		return new WP_REST_Response( $status, 200 );
	}

	/**
	 * Rest index test endpoint callback.
	 */
	public function rest_index_test( $request ) {
		if ( $this->async_test->check( sanitize_key( $_GET['key'] ) ) ) {
			$response = array(
				'success' => true,
			);
		} else {
			$response = array(
				'success' => false,
			);
		}

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Rest Bulk Delete endpoint callback.
	 */
	public function rest_bulk_delete_unused( $request ) {
		// If the indexer isn't running...
		if ( ! $this->is_bulk_processing() ) {

			// Get unhashed attachment IDs.
			$attachments = $this->get_attachment_ids( false, true );

			// Get total number of items to process.
			$total_count = count( $attachments );

			if ( $total_count < 1 ) {

				if ( $clean ) {
					return new WP_Error(
						'no_posts',
						__( 'There are no Unused media files on this site!', 'media-deduper' ),
						array(
							'status' => 200,
						)
					);
				}

				return;
			}

			// Kick off the indexer process.
			$this->bulk_delete_unused->process( $attachments );
		} elseif ( ! $this->bulk_delete_unused->is_processing() ) {
			return new WP_Error(
				'bulk_process_already_running',
				__( 'There is another Bulk Process already running, please wait until that process is complete or stop it!', 'media-deduper' ),
				array(
					'status' => 200,
				)
			);
		}//end if

		return new WP_REST_Response( __( 'Bulk Delete Started.', 'media-deduper' ), 200 );
	}

	/**
	 * Rest Bulk Delete status endpoint callback.
	 */
	public function rest_bulk_delete_unused_status( $request ) {
		// Get the stored status data.
		$status = $this->bulk_delete_unused->get_status();

		$pending_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => 'mdd_bulk_delete_unused_batch',
			), 'ids'
		);
		$pending_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => 'mdd_bulk_delete_unused_item',
			), 'ids'
		);

		$progress_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => 'mdd_bulk_delete_unused_batch',
			), 'ids'
		);
		$progress_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => 'mdd_bulk_delete_unused_item',
			), 'ids'
		);

		// there are pending actions we are processing, else lets complete and pass the complete status.
		if ( ! empty( $pending_batches ) || ! empty( $pending_items ) || ! empty( $progress_batches ) || ! empty( $progress_items ) ) {
			$status['state'] = 'processing';
		}

		return new WP_REST_Response( $status, 200 );
	}

	/**
	 * Rest Bulk Delete stop endpoint callback.
	 */
	public function rest_bulk_delete_unused_stop( $request ) {
		$status = $this->bulk_delete_unused->stop()->get_status();

		return new WP_REST_Response( $status, 200 );
	}

	/**
	 * Rest Bulk Delete test endpoint callback.
	 */
	public function rest_bulk_delete_unused_test( $request ) {
		if ( $this->async_test->check( sanitize_key( $_GET['key'] ) ) ) {
			$response = array(
				'success' => true,
			);
		} else {
			$response = array(
				'success' => false,
			);
		}

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Rest Smart Bulk Delete endpoint callback.
	 */
	public function rest_smart_bulk_delete( $request ) {
		// If the indexer isn't running...
		if ( ! $this->is_bulk_processing() ) {

			// Get unhashed attachment IDs.
			$attachments = $this->get_duplicate_ids( true );

			// Get total number of items to process.
			$total_count = count( $attachments );

			if ( $total_count < 1 ) {

					return new WP_Error(
						'no_posts',
						__( 'There are no duplicates on this site!', 'media-deduper' ),
						array(
							'status' => 200,
						)
					);
			}

			// Kick off the indexer process.
			$this->smart_bulk_delete->process( $attachments );
		} elseif ( ! $this->smart_bulk_delete->is_processing() ) {
			return new WP_Error(
				'bulk_process_already_running',
				__( 'There is another Bulk Process already running, please wait until that process is complete or stop it!', 'media-deduper' ),
				array(
					'status' => 200,
				)
			);
		}//end if

		return new WP_REST_Response( __( 'Smart Bulk Delete Process Started.', 'media-deduper' ), 200 );
	}

	/**
	 * Rest Smart Bulk Delete status endpoint callback.
	 */
	public function rest_smart_bulk_delete_status( $request ) {
		// Get the stored status data.
		$status = $this->smart_bulk_delete->get_status();

		$pending_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => 'mdd_smart_bulk_delete_batch',
			), 'ids'
		);
		$pending_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => 'mdd_smart_bulk_delete_item',
			), 'ids'
		);

		$progress_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => 'mdd_smart_bulk_delete_batch',
			), 'ids'
		);
		$progress_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => 'mdd_smart_bulk_delete_item',
			), 'ids'
		);

		// there are pending actions we are processing, else lets complete and pass the complete status.
		if ( ! empty( $pending_batches ) || ! empty( $pending_items ) || ! empty( $progress_batches ) || ! empty( $progress_items ) ) {
			$status['state'] = 'processing';
		}

		return new WP_REST_Response( $status, 200 );
	}

	/**
	 * Rest Smart Bulk Delete stop endpoint callback.
	 */
	public function rest_smart_bulk_delete_stop( $request ) {
		$status = $this->smart_bulk_delete->stop()->get_status();

		return new WP_REST_Response( $status, 200 );
	}

	/**
	 * Rest Smart Bulk Delete test endpoint callback.
	 */
	public function rest_smart_bulk_delete_test( $request ) {
		if ( $this->async_test->check( sanitize_key( $_GET['key'] ) ) ) {
			$response = array(
				'success' => true,
			);
		} else {
			$response = array(
				'success' => false,
			);
		}

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Get indexer status data.
	 */
	function ajax_index_status() {
		$status = $this->indexer->get_status();
		wp_send_json( $status );
	}

	/**
	 * Stop the indexer and return the indexer status.
	 */
	public function ajax_index_stop() {
		check_admin_referer( 'mdd_index_stop', 'nonce' );
		$status = $this->indexer->stop()->get_status();
		wp_send_json( $status );
	}

	/**
	 * Check whether an async test task has been executed.
	 */
	public function ajax_async_test() {
		if ( $this->async_test->check( sanitize_key( $_GET['key'] ) ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Calculate the size for a given file.
	 *
	 * @param string $file The path to the file for which to calculate size.
	 */
	private function calculate_size( $file ) {
		return filesize( $file );
	}

	/**
	 * Calculate the MD5 hash for a given file.
	 *
	 * @param string $file The path to the file for which to to calculate a hash.
	 */
	private function calculate_hash( $file ) {
		// If the user has selected to use partial hashes, only hash part of each file.
		if ( (bool) get_option( 'mdd_pro_general_section_run_partial_hashes' ) ) {
			/**
			 * Filter the maximum number of bytes to read when hashing attachment files.
			 *
			 * When the "run partial hashes" plugin setting is enabled, Media Deduper Pro will read this
			 * many bytes of a file, calculate its hash based on that data, and ignore the rest of the
			 * file. The lower this value is, the faster indexing will be, but very low values will
			 * increase the risk that two files could be treated as duplicates when they aren't really
			 * identical beyond their first few bytes.
			 *
			 * @param int    $length The length to read in bytes.
			 * @param string $file   The path to the file being hashed.
			 */
			$maxlength = apply_filters( 'mdd_pro_file_hash_maxlength', 5242880, $file );
			// Using file_get_contents as it uses memory mapping methods for performance. the other option is to use fopen and fread for this portion of the code.
			$file_contents = file_get_contents( $file, FILE_USE_INCLUDE_PATH, null, 0, $maxlength );

			return md5( $file_contents );
		}
		return md5_file( $file );
	}

	/**
	 * Save metadata for an attachment.
	 *
	 * @param int    $post_id  The ID of the post for which to save metadata.
	 * @param any    $value    The meta value to save.
	 * @param string $meta_key The meta key under which to save the value.
	 */
	private function save_media_meta( $post_id, $value, $meta_key = 'mdd_hash' ) {
		return update_post_meta( $post_id, $meta_key, $value );
	}

	/**
	 * Return either the total # of attachments, or the # of indexed attachments.
	 *
	 * @param string $type The type of count to return. Use 'all' to count all
	 *                     attachments, or 'indexed' to count only attachments
	 *                     whose hash and size have already been calculated,
	 *                     'unused' for unused images count, 'duplicates' for
	 *                     count of duplicates. Default 'all'.
	 */
	private function get_count( $type = 'all' ) {

		global $wpdb;

		// Get all trackable post type slugs.
		$escaped_post_types = $this->get_post_types_sql();

		switch ( $type ) {
			case 'all':
				$sql = "SELECT COUNT(*) FROM $wpdb->posts
					WHERE post_type = 'attachment'
						OR post_type IN ( $escaped_post_types );";
				break;

			case 'unused':
				$sql = "
						SELECT COUNT(*) FROM $wpdb->posts p
						WHERE p.post_type = 'attachment'
						AND ( EXISTS (
							SELECT * FROM $wpdb->postmeta pm
							WHERE pm.meta_key = '_mdd_referenced_by_count'
							AND pm.post_id = p.ID
							AND pm.meta_value = 0
						) )
						";
				break;

			case 'duplicates':
				$sql = "SELECT COUNT( DISTINCT p.post_id )
						FROM $wpdb->postmeta AS p
						JOIN (
							SELECT count(*) AS dupe_count, meta_value
							FROM $wpdb->postmeta
							WHERE meta_key = 'mdd_hash'
							AND meta_value != '" . self::NOT_FOUND_HASH . "'
							GROUP BY meta_value
							HAVING dupe_count > 1
						) AS p2
						ON p.meta_value = p2.meta_value;";
				break;

			case 'indexed':
			default:
				$sql = "SELECT COUNT(*) FROM $wpdb->posts p
					LEFT JOIN $wpdb->postmeta ph
						ON p.ID = ph.post_id AND ph.meta_key = 'mdd_hash'
					LEFT JOIN $wpdb->postmeta ps
						ON p.ID = ps.post_id AND ps.meta_key = 'mdd_size'
					LEFT JOIN $wpdb->postmeta pr
						ON p.ID = pr.post_id AND pr.meta_key = '_mdd_references'
					WHERE (
						p.post_type = 'attachment'
						AND ph.meta_id IS NOT NULL
						AND ps.meta_id IS NOT NULL
					) OR (
						p.post_type IN ( $escaped_post_types )
						AND pr.meta_id IS NOT NULL
					)
				";
		}//end switch

		$result = get_transient( 'mdd_count_' . $type );

		// Because a prior version of MDD Pro had a very sad bug that caused DB errors when calculating
		// the indexed count, we may have a stored transient that's just an empty string. If that
		// happens, recalculate the count.
		if ( false === $result || ! is_numeric( $result ) ) {
			$result = $wpdb->get_var( $sql );
			set_transient( 'mdd_count_' . $type, $result, HOUR_IN_SECONDS );
		}
		return $result;

	}

	/**
	 * Add to admin menu.
	 */
	function add_admin_menu() {
		$this->hook = add_media_page( __( 'Manage Duplicates', 'media-deduper' ), __( 'Manage Duplicates', 'media-deduper' ), $this->capability, 'media-deduper', array( $this, 'admin_screen' ) );

		add_action( 'load-' . $this->hook, array( $this, 'screen_tabs' ) );
	}

	/**
	 * Implements screen options.
	 */
	function screen_tabs() {

		$option = 'per_page';
		$args = array(
			'label'   => 'Items',
			'default' => get_option( 'posts_per_page', 20 ),
			'option'  => 'mdd_per_page',
		);
		add_screen_option( $option, $args );

		$screen = get_current_screen();

		$screen->add_help_tab(
			array(
				'id'      => 'overview',
				'title'   => __( 'Overview' ),
				'content' =>
				'<p>' . __( 'Media Deduper Pro was built to help you find and eliminate duplicate images and attachments from your WordPress media library.' )
					. '</p><p>' . __( 'Before Media Deduper Pro can identify duplicate assets, it first must build an index of all the files in your media library.' )
					. '</p><p>' . __( 'Once its index is complete, Media Deduper will also prevent users from uploading duplicates of files already present in your media library.' )
					. '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'      => 'indexing',
				'title'   => __( 'Indexing' ),
				'content' =>
				'<p>' . __( 'Media Deduper needs to generate an index of your media files in order to determine which files match, and an index of which posts reference which media files in order to swap out duplicate items with their originals. When indexing media, it only looks at the files themselves, not any data in WordPress (such as title, caption or comments). Once the initial index is built, Media Deduper automatically adds new uploads to its index and detects references to attachments in posts as they are created or edited, so you shouldn’t have to generate the index again.' )
					. '</p><p>' . __( 'As a part of the indexing process, Media Deduper also stores information about each file’s size so duplicates can be sorted by disk space used, allow you to most efficiently perform cleanup.' )
					. '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'      => 'deletion',
				'title'   => __( 'Deletion' ),
				'content' =>
				'<p>' . __( 'Once Media Deduper has indexed your files and found duplicates, you can easily delete them in one of two ways:' )
					. '</p><p>' . __( 'Option 1: Smart Delete. This option preserves references to images in post content, post excerpts, and certain post metadata fields (see the <a href="https://cornershop-creative.groovehq.com/knowledge_base/topics/using-media-deduper-pro">online documentation</a> for more information). Smart Delete replaces references to duplicate images with references to a single instance of the image, and only deletes orphaned copies of that image. Smart Delete will refuse to delete the last remaining copy of an item: even if you select all copies of an image, and none of them are used anywhere on the site, Smart Delete will leave one copy of the image in your library. In this sense, Smart Delete is safer than Delete Permanently. <em><strong>Please note:</strong></em> Although this option preserves featured images, post body content and excerpts, and a growing list of post meta fields, it does not currently replace attachment references in user meta, widgets, or site/network options, and it is not reversible. Please be careful.' )
					. '</p><p>' . __( 'Option 2: Delete Permanently. This option <em>permanently</em> deletes whichever files you select. This can be <em>very dangerous</em> as it cannot be undone, and you may inadvertently delete all versions of a file, regardless of how they are being used on the site.' )
					. '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'      => 'shared',
				'title'   => __( 'Shared Files' ),
				'content' =>
				'<p>' . __( 'In a typical WordPress installation, each different Media "post" relates to a separate file uploaded to the filesystem. However, some plugins facilitate copying media posts in a way that produces multiple posts all referencing a single file.' )
					. '</p><p>' . __( 'Media Deduper considers such posts to be "duplicates" because they share the same image data. However, in most cases you would not want to actually delete any of these posts because deleting any one of them would remove the media file they all share.' )
					. '</p><p>' . __( 'Because this can lead to unintentional data loss, Media Deduper prefers to suppress showing duplicates that share a file. However, it is possible to show these media items if you wish to review or delete them. <strong>Be extremely cautious</strong> when working with duplicates that share files as unintentional data loss can easily occur.' )
					. '</p>',
			)
		);

		$screen->add_help_tab(
			array(
				'id'      => 'about',
				'title'   => __( 'About' ),
				'content' =>
				'<p>' . __( 'Media Deduper was built by Cornershop Creative, on the web at <a href="https://cornershopcreative.com">https://cornershopcreative.com</a>' )
					. '</p><p>' . __( 'Need support? Got a feature idea? Contact us at <a href="mailto:support@cornershopcreative.com">support@cornershopcreative.com</a>, or check out our <a href="https://cornershop-creative.groovehq.com/knowledge_base/categories/media-deduper">knowledge base</a>.' )
					. '</p>',
			)
		);

		$this->get_duplicate_ids();

		// We use $wp_query (the main query) since Media_List_Table does and we extend that.
		global $wp_query;

		// Defaults that $_GET can override.
		$query_defaults = array(
			'orderby'        => array(
				'mdd_size'  => 'desc',
				'post_date' => 'desc',
			),
		);

		// Get the currently active tab.
		$active_tab = $this->active_tab();

		$tab_query_settings_defaults = array(
			'post_type'      => 'attachment',
			'post_status'    => get_post_stati(),
			'posts_per_page' => get_user_option( 'mdd_per_page' ),
		);

		// Hard settings that should override anything in $_GET
		switch ( $active_tab ) {
			case 'duplicates':
				// search for attachments with ids that are duplicate
				$tab_specific_query_settings = array(
					'post__in'       => $this->duplicate_ids,
				);
				break;
			default:
				$tab_specific_query_settings = array();
		}

		$tab_query_settings = array_merge( $tab_query_settings_defaults, $tab_specific_query_settings );

		$query_parameters = array_merge(
			$query_defaults,
			// Query args (most of the time these will only affect sort order).
			$_GET,
			$tab_query_settings
		);

		// If suppressing shared files (the default), do that.
		if ( ( ! isset( $_GET['show_shared'] ) || 1 !== absint( $_GET['show_shared'] ) ) && 'duplicates' === $active_tab ) {
			$this->get_shared_filename_ids();
			$query_parameters['post__in'] = array_diff( $this->duplicate_ids, $this->shared_filename_ids );
			if ( ! count( $query_parameters['post__in'] ) ) {
				// We do this otherwise WP_Query's post__in gets an empty array and
				// returns all posts.
				$query_parameters['post__in'] = array( '0' );
			}
		}

		$wp_query = new WP_Query( $query_parameters );

		$this->list_table = new MDD_Pro_Media_List_Table(
			array(
				// Even though this is really the 'media_page_media-deduper' screen,
				// we want to show the columns that would normally be shown on the
				// 'upload' screen, including taxonomy terms or any other columns
				// that other plugins might be adding.
				'screen' => 'upload',
			)
		);

		// Handle bulk actions, if any.
		$this->handle_bulk_actions();

		// If we got here via a form submission, but there was no bulk action to apply, then the user
		// probably just changed the 'Hide duplicates that share files' setting. Redirect to a slightly
		// cleaner URL: remove the _wp_http_referer and _wpnonce args. wp-admin/upload.php does this.
		if ( ! empty( $_GET['_wp_http_referer'] ) && ! empty( $_GET['filter_action'] ) ) {
			$redirect_url = add_query_arg(
				array(
					'show_shared' => absint( $_GET['show_shared'] ),
				), admin_url( 'upload.php?page=media-deduper' )
			);
			wp_redirect( $redirect_url );
			exit;
		}
	}

	/**
	 * Allow the `mdd_per_page` screen option to be saved.
	 *
	 * @param bool|int $status Screen option value. Default false to skip.
	 * @param string   $option The option name.
	 * @param int      $value  The number of rows to use.
	 */
	function save_screen_options( $status, $option, $value ) {
		if ( 'mdd_per_page' === $option ) {
			return $value;
		}
		return $status;
	}

	/**
	 * The main admin screen!
	 */
	function admin_screen() {
		// MDD admin tabs
		$tabs = array(
			'duplicates' => __( 'Duplicates', 'media-deduper' ),
			'alt-text' => __( 'Missing Alt Text', 'media-deduper' ),
			'index' => __( 'Index', 'media-deduper' ),
			'bulk-delete-unused' => __( 'Bulk Delete Unused Images', 'media-deduper' ),
			'smart-bulk-delete' => __( 'Smart Bulk Delete Duplicates', 'media-deduper' ),
			'license' => __( 'License Key', 'media-deduper' ),
		);

		// Get the currently active tab.
		$active_tab = $this->active_tab();
		?>
		<div id="mdd-async-test-message" class="notice notice-info" style="display:none"></div>
		<div id="mdd-message" class="updated fade" style="display:none"></div>
		<div class="wrap deduper">
			<h1><?php esc_html_e( 'Media Deduper Pro', 'media-deduper' ); ?></h1>
			<aside class="mdd-column-2">
				<div class="mdd-box">
					<h2>Like Media Deduper?</h2>
					<ul>
						<li class="share"><a href="#" data-service="facebook">Share it on Facebook »</a></li>
						<li class="share"><a href="#" data-service="twitter">Tweet it »</a></li>
						<li><a href="https://wordpress.org/support/plugin/media-deduper/reviews/#new-post" target="_blank">Review it on WordPress.org »</a></li>
					</ul>
				</div>
			</aside>
			<div class="mdd-column-1">
				<div class="nav-tab-wrapper">
					<?php

					foreach ( $tabs as $key => $label ) :
						?>
					<a href="<?php echo esc_url( admin_url( "upload.php?page=media-deduper&tab=$key" ) ); ?>" class="nav-tab<?php echo ( $key === $active_tab ? ' nav-tab-active' : '' ); ?>"><?php echo $label; ?></a>
						<?php
					endforeach;
					?>
				</div>

		<?php
		if ( 'index' === $active_tab ) :
			?>

				<h2><?php esc_html_e( 'Index of Duplicate Media', 'media-deduper' ); ?></h2>

				<?php
				// Display the index screen.
				$this->show_index_screen();
				?>

			</div><!-- .mdd-column-1 -->

		<?php elseif ( 'bulk-delete-unused' === $active_tab ) : ?>

				<h2><?php esc_html_e( 'Bulk Delete Unused Images', 'media-deduper' ); ?></h2>

				<?php
				// Display the bulk delete screen.
				$this->show_bulk_delete_unused_screen();
				?>

			</div><!-- .mdd-column-1 -->

		<?php elseif ( 'smart-bulk-delete' === $active_tab ) : ?>

				<h2><?php esc_html_e( 'Smart Bulk Delete Duplicates', 'media-deduper' ); ?></h2>

				<?php
				// Display the bulk delete screen.
				$this->show_smart_bulk_delete_screen();
				?>

			</div><!-- .mdd-column-1 -->
		
		<?php elseif ( 'license' === $active_tab ) : ?>

				<h2><?php esc_html_e( 'License Key', 'media-deduper' ); ?></h2>

				<?php
				// Display the license key form.
				$this->license_manager->license_form();
				?>

			</div><!-- .mdd-column-1 -->

		<?php elseif ( 'alt-text' === $active_tab ) : ?>

				<h2><?php esc_html_e( 'Missing Alt Text', 'media-deduper' ); ?></h2>

			</div><!-- .mdd-column-1 -->

			<!-- the posts table -->
			<form id="posts-filter" method="get">
				<?php
				// Set the `page` query param when processing actions. This ensures that
				// $this->handle_bulk_actions() will run, which will process the bulk action and redirect
				// the user. Otherwise, it would fall to wp-admin/upload.php to process bulk actions, and
				// upload.php doesn't know how to smartdelete.
				?>
				<input type="hidden" name="page" value="media-deduper">
				<?php

				$this->list_table->prepare_items();
				$this->list_table->display();

				// This stuff makes the 'Attach' dialog work.
				wp_nonce_field( 'find-posts', '_ajax_nonce', false );
				?>
				<input type="hidden" id="find-posts-input" name="ps" value="" /><div id="ajax-response"></div>
				<?php find_posts_div(); ?>
			</form>

		<?php else : ?>

				<p><?php esc_html_e( 'Use this tool to identify duplicate media files in your site. It only looks at the files themselves, not any data in WordPress (such as title, caption or comments).', 'media-deduper' ); ?></p>
				<p><?php esc_html_e( 'In order to identify duplicate files, an index of all media must first be generated.', 'media-deduper' ); ?></p>

				<?php
				$this->show_bulk_process_button( 'index', 'indexed', $this->indexer, $this->indexer_labels );
				?>

			</div><!-- .mdd-column-1 -->

			<!-- the posts table -->
			<h2 style="clear:both;"><?php esc_html_e( 'Duplicate Media Files', 'media-deduper' ); ?></h2>
			<form id="posts-filter" method="get">
				<?php
				// Set the `page` query param when processing actions. This ensures that
				// $this->handle_bulk_actions() will run, which will process the bulk action and redirect
				// the user. Otherwise, it would fall to wp-admin/upload.php to process bulk actions, and
				// upload.php doesn't know how to smartdelete.
				?>
				<input type="hidden" name="page" value="media-deduper">
				<div class="wp-filter">
					<div class="view-switch">
						<select name="show_shared">
							<option value="0" <?php selected( ! isset( $_GET['show_shared'] ) || ( '0' === $_GET['show_shared'] ) ); ?>><?php esc_html_e( 'Hide duplicates that share files', 'media-deduper' ); ?></option>
							<option value="1" <?php selected( isset( $_GET['show_shared'] ) && ( '1' === $_GET['show_shared'] ) ); ?>><?php esc_html_e( 'Show duplicates that share files', 'media-deduper' ); ?></option>
						</select>
						<input type="submit" name="filter_action" id="post-query-submit" class="button" value="<?php esc_attr_e( 'Apply', 'media-deduper' ); ?>">
					</div>
					<a href="javascript:void(0);" id="shared-help"><?php esc_html_e( 'What\'s this?', 'media-deduper' ); ?></a>
				</div>
				<?php

				$this->list_table->prepare_items();
				$this->list_table->display();

				// This stuff makes the 'Attach' dialog work.
				wp_nonce_field( 'find-posts', '_ajax_nonce', false );
				?>
				<input type="hidden" id="find-posts-input" name="ps" value="" /><div id="ajax-response"></div>
				<?php find_posts_div(); ?>
			</form>
		<?php endif; ?>

		</div><!-- .wrap -->
		<?php
	}

	/**
	 * Output the bulk delete progress page.
	 */
	private function show_bulk_delete_unused_screen() {
		$action = 'bulk-delete-unused';
		if ( isset( $_GET['async_test'] ) ) {
			// Spawn an async task to check whether async processing is working.
			$async_test_key = uniqid();
			$this->async_test->run( $async_test_key );
			wp_localize_script( 'media-deduper-js', 'mdd_async_test_key', $async_test_key );
		}

		?>
		<p><?php esc_html_e( 'IMPORTANT! By default, Media Deduper only checks the following locations to determine if an image is in use:', 'media-deduper' ); ?></p>

		<ul>
			<?php
			$list_unused_locations = array(
				__( 'Featured Images', 'media-deduper' ),
				__( 'ACF image fields', 'media-deduper' ),
				__( 'Classic Editor images', 'media-deduper' ),
				__( 'Meta Data images', 'media-deduper' ),
				__( 'WooCommerce images', 'media-deduper' ),
				__( 'Core Gutenberg Block images (no third party blocks)', 'media-deduper' ),
			);

			foreach ( $list_unused_locations as $unused_location ) :
				?>
			<li><?php echo $unused_location; ?></li>
			<?php endforeach; ?>
		</ul>
		<p><?php esc_html_e( 'If an image is not used in any of these locations, but is referenced in some other way (e.g. in post meta fields not listed above, or in site options or widgets), Media Deduper may still identify the image as unused and delete it. For this reason, we strongly recommend that you back up your database and files before bulk deleting unused images.', 'media-deduper' ); ?></p>
		<?php

		// If no bulk process is running...
		// else if bulk delete isnt running then another process is running
		if ( ! $this->is_bulk_processing() ) {

			// If the user didn't get here by clicking the Index Media button, then show the button,
			// explanatory text, and any errors from the last time the index was run.
			if ( empty( $_POST[ "mdd-build-{$action}" ] ) ) {
				$this->show_bulk_process_button( $action, 'unused', $this->bulk_delete_unused, $this->bulk_delete_unused_labels, true );
				return;
			}

			// Form nonce check.
			check_admin_referer( "media-deduper-{$action}" );

			// Get unused attachment IDs.
			$attachments = $this->get_attachment_ids( false, true );

			// Get total number of items to process.
			$total_count = count( $attachments );

			if ( $total_count < 1 ) {

				echo '<p>' . esc_html__( 'There are no Unused media files on this site!', 'media-deduper' ) . '</p>';

				return;
			}

			// Kick off the indexer process.
			$this->bulk_delete_unused->process( array_merge( $attachments ) );
		} elseif ( ! $this->bulk_delete_unused->is_processing() ) {
			?>
			<p><?php esc_html_e( 'Media Deduper is running another Bulk Process. Please wait until the process is complete or stop the process if you need to run the Bulk Delete Unused images process.', 'media-deduper' ); ?></p>
			<?php
			// return dont dsiplay the bar.
			return;
		}//end if

		?>
		<p><?php esc_html_e( 'Please be patient while the Bulk Delete Unused images process is active. This can take a while if your server is slow or if you have many large media files. Once the process is underway, it will continue running on its own. You may close this window or navigate away from this page. You may return later to check on the process.', 'media-deduper' ); ?></p>

		<noscript><p><em><?php esc_html_e( 'You must enable Javascript in order to proceed!', 'media-deduper' ); ?></em></p></noscript>

		<div id="mdd-bar" style="visibility: hidden;" data-action="bulk_delete_unused">
			<div id="mdd-meter"></div>
			<div id="mdd-bar-percent"></div>
		</div>

		<p>
			<button class="button hide-if-no-js" id="mdd-stop">
				<?php esc_attr_e( 'Stop', 'media-deduper' ); ?>
			</button>
			<a class="button" id="mdd-manage" href="<?php echo esc_url( admin_url( 'upload.php?page=media-deduper' ) ); ?>"><?php esc_attr_e( 'Manage Duplicates Now', 'media-deduper' ); ?></a>
		</p>

		<div class="error-files">
			<ul></ul>
		</div>

		<?php

		wp_localize_script( 'media-deduper-js', 'mdd_bulk_process_status', $this->bulk_delete_unused->get_status() );
		wp_localize_script( 'media-deduper-js', 'mdd_bulk_process_stop_nonce', wp_create_nonce( 'mdd_bulk_process_stop' ) );
	}

	/**
	 * Process an item in the bulk delete queue.
	 *
	 * @param int $post_id The ID of the post to bulk delete.
	 */
	public function bulk_delete_unused_item( $post_id ) {
		// If the user has asked the indexer to stop, skip this item.
		if ( $this->bulk_delete_unused->is_stopped() ) {
			return false;
		}

		// Sanitize $post_id.
		$post_id = absint( $post_id );
		// Get post data.
		$post = get_post( $post_id );

		// Get the stored status data.
		$status = $this->bulk_delete_unused->get_status();

		if ( ! $post ) {

			// If this isn't really a post, skip it.
			$status['failed'] += 1;
			$status['error_messages'][] = sprintf(
				// translators: %d: The ID of the missing post.
				__( 'No post found with ID %d', 'media-deduper' ),
				$post_id
			);

		} else {

			// Before deleting the attachment post, check whether this attachment's file is also used by
			// another attachment.
			$other_attachment_id = $this->get_same_file_attachment_id( $post_id );

			// If the file is in use by another attachment, use `__return_false()` on the `wp_delete_file`
			// hook to prevent file deletion -- unless some OTHER plugin has already done that.
			$is_blocking_file_deletion = false;
			if ( ! empty( $other_attachment_id ) && ! has_filter( 'wp_delete_file', '__return_false' ) ) {
				$is_blocking_file_deletion = true;
				add_filter( 'wp_delete_file', '__return_false' );
			}

			// Finally, delete this attachment. The second argument here ($force_delete) causes the
			// attachment to be deleted immediately, regardless of the user's MEDIA_TRASH setting. The
			// duplicates list table displays trashed and non-trashed attachments together, and allowing the
			// normal MEDIA_TRASH behavior would make bulk actions affect trashed and non-trashed
			// attachments differently: non-trashed attachments would be trashed rather than deleted, and
			// would continue to appear in the list table after being "deleted." in order to fully remove
			// them, the user would have to "delete" them a second time.
			$result = wp_delete_attachment( $post_id, true );

			// If we blocked deletion of files earlier, restore normal functionality. Note that since we're
			// checking the $is_blocking_file_deletion variable, `__return_false()` will only be removed if
			// MDD was responsible for adding it -- meaning this should not interfere with any other code
			// that prevents file deletion the same way.
			if ( $is_blocking_file_deletion ) {
				remove_filter( 'wp_delete_file', '__return_false' );
			}

			// if a post object is not returned then there was an error.
			if ( ! $result instanceof WP_Post ) {
				$status['failed'] += 1;
				// Add the latest message.
				$status['error_messages'][] = sprintf( __( 'There was an error deleting the attachment id %d' ), $post_id );
			}
		}//end if

		// Bump the processed count.
		$status['processed'] += 1;

		// Store the updated status data.
		$this->bulk_delete_unused->update_status( $status );

		// if we have processed all the items lets complete the status.
		if ( $status['processed'] === $status['total'] ) {
			$this->bulk_delete_unused->complete();
			$this->delete_transients();
		}
	}

	/**
	 * Output the smart bulk delete progress page.
	 */
	private function show_smart_bulk_delete_screen() {
		$action = 'smart-bulk-delete';
		if ( isset( $_GET['async_test'] ) ) {
			// Spawn an async task to check whether async processing is working.
			$async_test_key = uniqid();
			$this->async_test->run( $async_test_key );
			wp_localize_script( 'media-deduper-js', 'mdd_async_test_key', $async_test_key );
		}

		// If the indexer isn't running...
		if ( ! $this->is_bulk_processing() ) {

			// If the user didn't get here by clicking the Index Media button, then show the button,
			// explanatory text, and any errors from the last time the index was run.
			if ( empty( $_POST[ "mdd-build-{$action}" ] ) ) {
				$this->show_bulk_process_button( $action, 'duplicates', $this->smart_bulk_delete, $this->smart_bulk_delete_labels, true );
				return;
			}

			// Form nonce check.
			check_admin_referer( "media-deduper-{$action}" );

			// Check whether _all_ posts should be indexed ($clean === true), or only un-indexed ones.
			$clean = ( ! empty( $_POST['mdd-build-index-clean'] ) );

			// Get duplicate attachment IDs.
			$attachments = $this->get_duplicate_ids();

			// Get total number of items to process.
			$total_count = count( $attachments );

			if ( $total_count < 1 ) {

					echo '<p>' . esc_html__( 'There are no duplicates on this site!', 'media-deduper' ) . '</p>';

				return;
			}

			// Kick off the indexer process.
			$this->smart_bulk_delete->process( $attachments );
		} elseif ( ! $this->smart_bulk_delete->is_processing() ) {
			?>
			<p><?php esc_html_e( 'Media Deduper is running another Bulk Process. Please wait until the process is complete or stop the process if you need to run the Smart Bulk Delete process.', 'media-deduper' ); ?></p>
			<?php
			// return dont dsiplay the bar.
			return;
		}//end if

		?>
		<p><?php esc_html_e( 'Please be patient while the Smart Bulk Delete process is active. This can take a while if your server is slow or if you have many large media files. Once the process is underway, it will continue running on its own. You may close this window or navigate away from this page. You may return later to check on the process.', 'media-deduper' ); ?></p>

		<noscript><p><em><?php esc_html_e( 'You must enable Javascript in order to proceed!', 'media-deduper' ); ?></em></p></noscript>

		<div id="mdd-bar" style="visibility: hidden;" data-action="smart_bulk_delete">
			<div id="mdd-meter"></div>
			<div id="mdd-bar-percent"></div>
		</div>

		<p>
			<button class="button hide-if-no-js" id="mdd-stop">
				<?php esc_attr_e( 'Stop', 'media-deduper' ); ?>
			</button>
			<a class="button" id="mdd-manage" href="<?php echo esc_url( admin_url( 'upload.php?page=media-deduper' ) ); ?>"><?php esc_attr_e( 'Manage Duplicates Now', 'media-deduper' ); ?></a>
		</p>

		<div class="error-files">
			<ul></ul>
		</div>

		<?php

		wp_localize_script( 'media-deduper-js', 'mdd_bulk_process_status', $this->smart_bulk_delete->get_status() );
		wp_localize_script( 'media-deduper-js', 'mdd_bulk_process_stop_nonce', wp_create_nonce( 'mdd_bulk_process_stop' ) );
	}

	/**
	 * Process an item in the smart bulk delete queue.
	 *
	 * @param int $post_id The ID of the post to smart bulk delete.
	 */
	public function smart_bulk_delete_item( $post_id ) {
		// If the user has asked the indexer to stop, skip this item.
		if ( $this->smart_bulk_delete->is_stopped() ) {
			return false;
		}

		// Sanitize $post_id.
		$post_id = absint( $post_id );
		// Get post data.
		$post = get_post( $post_id );

		// Get the stored status data.
		$status = $this->smart_bulk_delete->get_status();

		if ( ! $post ) {

			// If this isn't really a post, skip it.
			$status['failed'] += 1;
			$status['error_messages'][] = sprintf(
				// translators: %d: The ID of the missing post.
				__( 'No post found with ID %d', 'media-deduper' ),
				$post_id
			);

		} else {

			// If the post was found, process it.
			// Get the global MDD plugin object.
			$result = self::smart_delete_media( $post_id );
		}//end if

		// Bump the processed count.
		$status['processed'] += 1;

		// Store the updated status data.
		$this->smart_bulk_delete->update_status( $status );

		// if we have processed all the items lets complete the status.
		if ( $status['processed'] === $status['total'] ) {
			$this->smart_bulk_delete->complete();
			$this->delete_transients();
		}
	}

	/**
	 * Output the indexing progress page.
	 */
	private function show_index_screen() {

		if ( isset( $_GET['async_test'] ) ) {
			// Spawn an async task to check whether async processing is working.
			$async_test_key = uniqid();
			$this->async_test->run( $async_test_key );
			wp_localize_script( 'media-deduper-js', 'mdd_async_test_key', $async_test_key );
		}

		// If the indexer isn't running...
		if ( ! $this->is_bulk_processing() ) {

			// If the user didn't get here by clicking the Index Media button, then show the button,
			// explanatory text, and any errors from the last time the index was run.
			if ( empty( $_POST['mdd-build-index'] ) && empty( $_POST['mdd-build-index-clean'] ) ) {
				$this->show_bulk_process_button( 'index', 'indexed', $this->indexer, $this->indexer_labels, true );
				return;
			}

			// Form nonce check.
			check_admin_referer( 'media-deduper-index' );

			// Check whether _all_ posts should be indexed ($clean === true), or only un-indexed ones.
			$clean = ( ! empty( $_POST['mdd-build-index-clean'] ) );

			// Get unhashed attachment IDs.
			$attachments = $this->get_attachment_ids( ! $clean );
			// Get untracked post IDs.
			$posts = $this->get_post_ids();
			// Get total number of items to process.
			$total_count = count( $attachments ) + count( $posts );

			if ( $total_count < 1 ) {

				if ( $clean ) {
					echo '<p>' . esc_html__( 'There are no indexable posts or attachments on this site! As you begin adding content, Media Deduper Pro will index the new content in the background.', 'media-deduper' ) . '</p>';
				} else {
					echo '<p>' . esc_html__( 'There are no unindexed items. Would you like to completely rebuild the index?', 'media-deduper' ) . '</p>';
					$this->show_bulk_process_button( 'index', 'indexed', $this->indexer, $this->indexer_labels, true, false );
				}

				return;
			}

			// Kick off the indexer process.
			$this->indexer->process( array_merge( $attachments, $posts ) );
		} elseif ( ! $this->indexer->is_processing() ) {
			?>
			<p><?php esc_html_e( 'Media Deduper is running another Bulk Process. Please wait until the process is complete or stop the process if you need to run the Indexer.', 'media-deduper' ); ?></p>
			<?php
			// return dont dsiplay the bar.
			return;
		}//end if

		?>
		<p><?php esc_html_e( 'Please be patient while the Indexer is active. This can take a while if your server is slow or if you have many large media files. Once the process is underway, it will continue running on its own. You may close this window or navigate away from this page. You may return later to check on the process.', 'media-deduper' ); ?></p>

		<noscript><p><em><?php esc_html_e( 'You must enable Javascript in order to proceed!', 'media-deduper' ); ?></em></p></noscript>

		<div id="mdd-bar" style="visibility: hidden;" data-action="index">
			<div id="mdd-meter"></div>
			<div id="mdd-bar-percent"></div>
		</div>

		<p>
			<button class="button hide-if-no-js" id="mdd-stop">
				<?php esc_attr_e( 'Stop', 'media-deduper' ); ?>
			</button>
			<a class="button" id="mdd-manage" href="<?php echo esc_url( admin_url( 'upload.php?page=media-deduper' ) ); ?>"><?php esc_attr_e( 'Manage Duplicates Now', 'media-deduper' ); ?></a>
		</p>

		<div class="error-files">
			<ul></ul>
		</div>

		<?php

		wp_localize_script( 'media-deduper-js', 'mdd_bulk_process_status', $this->indexer->get_status() );
		wp_localize_script( 'media-deduper-js', 'mdd_bulk_process_stop_nonce', wp_create_nonce( 'mdd_bulk_process_stop' ) );
	}

	/**
	 * Process an item in the index queue.
	 *
	 * @param int $post_id The ID of the post to index.
	 */
	public function index_item( $post_id ) {
		// If the user has asked the indexer to stop, skip this item.
		if ( $this->indexer->is_stopped() ) {
			return false;
		}

		// Sanitize $post_id.
		$post_id = absint( $post_id );
		// Get post data.
		$post = get_post( $post_id );

		// Get the stored status data.
		$status = $this->indexer->get_status();

		if ( ! $post ) {

			// If this isn't really a post, skip it.
			$status['failed'] += 1;
			$status['error_messages'][] = sprintf(
				// translators: %d: The ID of the missing post.
				__( 'No post found with ID %d', 'media-deduper' ),
				$post_id
			);

		} else {

			// If the post was found, process it.
			// Get the global MDD plugin object.
			global $media_deduper_pro;

			if ( 'attachment' === $post->post_type ) {
				// If this is an attachment, calculate its hash.
				$result = $media_deduper_pro->calc_media_meta( $post_id );
				// If it doesn't already have an _mdd_referenced_by_count meta value, add one. Without this,
				// we can't sort by # of references -- or we could with custom SQL clauses, but it'd be more
				// trouble than it's worth.
				if ( '' === get_post_meta( $post_id, '_mdd_referenced_by_count', true ) ) {
					update_post_meta( $post_id, '_mdd_referenced_by_count', 0 );
				}
			} else {
				// If this isn't an attachment, check for references to attachments and store them.
				$result = $media_deduper_pro->track_media_refs( $post_id );
			}

			if ( is_wp_error( $result ) ) {
				$status['failed'] += 1;
				// Add the latest message.
				$status['error_messages'][] = $result->get_error_message();
			}
		}//end if

		// Bump the processed count.
		$status['processed'] += 1;

		// Store the updated status data.
		$this->indexer->update_status( $status );

		// if we have processed all the items lets complete the status.
		if ( $status['processed'] === $status['total'] ) {
			$this->indexer->complete();
			$this->delete_transients();
		}
	}

	/**
	 * Output the (Re-)Index Media button and preceding text.
	 *
	 * @param bool $always    Set to TRUE if the button should be displayed even if there are no
	 *                        un-indexed attachments. Default FALSE.
	 * @param bool $show_info Set to FALSE if only the button should be shown. Otherwise, contextual
	 *                        information will be added before and/or after the button, detailing the
	 *                        number of unindexed items, etc.
	 */
	private function show_bulk_process_button( $action = 'index', $count_type = 'indexed', $bulk_processor = '', $labels = array(), $always = false, $show_info = true ) {

		$bulk_process_incomplete = ( $this->get_count( "{$count_type}" ) < $this->get_count() );

		if ( $bulk_process_incomplete ) {

			if ( $show_info ) {
				if ( isset( $labels['items-processed'] ) || isset( $labels['call-to-action'] ) ) {
					// If the index isn't comprehensive, show how many attachments have been indexed.
					?>
				<p>
					<?php
					if ( isset( $labels['items-processed'] ) ) {
						echo esc_html( $labels['items-processed'] );
					}
					if ( isset( $labels['call-to-action'] ) ) {
						?>
						<strong><?php echo esc_html( $labels['call-to-action'] ); ?></strong>
						<?php
					}
					?>
				</p>
					<?php
				}
			}

			$button_text = isset( $labels['submit'] ) ? $labels['submit'] : __( 'Process', 'media-deduper' );
			$button_name = "mdd-build-{$action}";

		} else {
			// If the index IS comprehensive, say so.
			if ( $show_info ) {
				// Get and display errors from the last indexer run, if any.
				$last_index_status = $bulk_processor->get_status();
				$date = isset( $last_index_status['date'] ) ? $last_index_status['date'] : '';

				// If the date exists output it otherwise default back to the previous message used for backwards compatibility.
				if ( ! empty( $date ) ) {
					?>
					<p>
					<?php
						echo sprintf(
							// translators: %s date of last full index completed
							isset( $labels['comprehensive-info'] ) ? $labels['comprehensive-info'] : __( 'Last full process completed on %s.', 'media-deduper' ),
							$date
						);
					?>
					</p>
					<?php
				} else {
					?>
					<p><?php echo esc_html( isset( $labels['none-comprehensive-info'] ) ? $labels['none-comprehensive-info'] : __( 'All items have been processed.', 'media-deduper' ) ); ?></p>
					<?php
				}
			}//end if

			$button_text = isset( $labels['re-submit'] ) ? $labels['re-submit'] : __( 'Re-Process', 'media-deduper' );
			$button_name = "mdd-build-{$action}-clean";

		}//end if

		// Show the button, if ether the index isn't comprehensive or we were asked to always show it.
		if ( $always || $bulk_process_incomplete ) {
			?>
			<form method="post" action="<?php echo esc_url( admin_url( "upload.php?page=media-deduper&tab={$action}" ) ); ?>">
				<?php wp_nonce_field( "media-deduper-{$action}" ); ?>
				<p><input type="submit" class="button hide-if-no-js" name="<?php echo esc_attr( $button_name ); ?>" value="<?php echo esc_attr( $button_text ); ?>" /></p>
				<noscript><p><em><?php esc_html_e( 'You must enable Javascript in order to proceed!', 'media-deduper' ); ?></em></p></noscript>
			</form><br>
			<?php
		}

		if ( $always && $show_info ) {

			// Get and display errors from the last indexer run, if any.
			$last_index_status = $bulk_processor->get_status();
			$errors = $last_index_status['error_messages'];

			if ( ! empty( $errors ) ) {

				if ( 'stopped' === $last_index_status['state'] ) {
					// translators: %d: The number of errors.
					$error_heading = isset( $labels['stopped-heading'] ) ? $labels['stopped-heading'] : __( 'The last process, which was stopped manually, resulted in %d errors:', 'media-deduper' );
				} else {
					// translators: %d: The number of errors.
					$error_heading = isset( $labels['error-heading'] ) ? $labels['error-heading'] : __( 'The last process resulted in %d errors:', 'media-deduper' );
				}

				echo '<h4>' . esc_html(
					sprintf(
						$error_heading,
						count( $errors )
					)
				) . '</h4>';

				?>
				<div class="error-files">
					<ul>
						<?php foreach ( $errors as $error ) { ?>
							<li>
							<?php
							echo wp_kses(
								$error,
								array(
									'a' => array(
										'href' => array(),
										'title' => array(),
									),
								)
							);
							?>
							</li>
						<?php } ?>
					</ul>
				</div>
				<?php
			}//end if
		}//end if
	}

	/**
	 * Retrieves a list of attachment posts that haven't yet had their file md5 hashes computed.
	 *
	 * @param bool $unhashed_only TRUE to return the IDs of attachments whose hash has not been
	 *                            calculated. FALSE to return all attachment IDs.
	 * @param bool $unused_only   TRUE to return the IDs of attachments MDD sees as unused
	 *                            FALSE to return both used and unused attachment IDs.
	 *
	 * @return array
	 */
	private function get_attachment_ids( $unhashed_only = true, $unused_only = false ) {

		global $wpdb;

		$sql = "
			SELECT ID FROM $wpdb->posts p
			WHERE p.post_type = 'attachment'
			";

		if ( $unhashed_only ) {
			$sql .= "
				AND ( NOT EXISTS (
					SELECT * FROM $wpdb->postmeta pm
					WHERE pm.meta_key = 'mdd_hash'
					AND pm.post_id = p.ID
				) OR NOT EXISTS (
					SELECT * FROM $wpdb->postmeta pm2
					WHERE pm2.meta_key = 'mdd_size'
					AND pm2.post_id = p.ID
				) )
				";
		}

		if ( $unused_only ) {
			$sql .= "
				AND ( EXISTS (
					SELECT * FROM $wpdb->postmeta pm
					WHERE pm.meta_key = '_mdd_referenced_by_count'
					AND pm.post_id = p.ID
					AND pm.meta_value = 0
				) )
				";
		}

		$sql .= ';';

		return $wpdb->get_col( $sql );
	}

	/**
	 * Retrieves the IDs of all posts that should be indexed.
	 *
	 * Note that unlike get_attachment_ids(), this doesn't provide a way to only get the IDs of
	 * un-indexed posts. That's because it's better to err on the side of caution, and re-index ALL
	 * posts when the user triggers the indexer manually. Usually the user will only rebuild the index
	 * if they've just reactivated the plugin after leaving it inactive for a while, and unless we
	 * reindex everything, we won't catch any changes that were made to post content while the plugin
	 * was inactive.
	 */
	private function get_post_ids() {

		global $wpdb;

		// Get all trackable post type slugs.
		$escaped_post_types = $this->get_post_types_sql();

		$sql = "SELECT ID FROM $wpdb->posts p
			WHERE p.post_type IN ( $escaped_post_types );";

		return $wpdb->get_col( $sql );
	}

	/**
	 * Return a comma-separated list of quoted post type slugs for use in a SQL IN (...) clause.
	 */
	private function get_post_types_sql() {

		// Get all trackable post types.
		$post_types = $this->reference_handler->get_post_types();

		// Build a comma-separated list of escaped, quoted post type slugs.
		$escaped_post_types = "'" . join( "', '", array_map( 'esc_sql', $post_types ) ) . "'";

		return $escaped_post_types;
	}

	/**
	 * Retrieves an array of post ids that have duplicate hashes.
	 *
	 * @param bool $single_duplicates False to return all duplicates. True to return a single one per duplicate group.
	 *
	 * @return array
	 */
	private function get_duplicate_ids( $single_duplicate = false ) {

		global $wpdb;

		$duplicate_ids = get_transient( 'mdd_duplicate_ids' );

		if ( false === $duplicate_ids ) {
			$sql = "SELECT DISTINCT p.post_id
				FROM $wpdb->postmeta AS p
				JOIN (
					SELECT count(*) AS dupe_count, meta_value
					FROM $wpdb->postmeta
					WHERE meta_key = 'mdd_hash'
					AND meta_value != '" . self::NOT_FOUND_HASH . "'
					GROUP BY meta_value
					HAVING dupe_count > 1
				) AS p2
				ON p.meta_value = p2.meta_value";

			// if we want to return a single duplicate per group of duplicates
			// group them by the meta value.
			if ( $single_duplicate ) {
				$sql .= ' GROUP BY p.meta_value';
			}

			// Close the query.
			$sql .= ';';

			$duplicate_ids = $wpdb->get_col( $sql );
			// If we don't do this, WP_Query's post__in gets an empty array and
			// returns all posts.
			if ( ! count( $duplicate_ids ) ) {
				$duplicate_ids = array( '0' );
			}
			set_transient( 'mdd_duplicate_ids', $duplicate_ids, HOUR_IN_SECONDS );
		}//end if

		$this->duplicate_ids = $duplicate_ids;
		return $this->duplicate_ids;

	}

	/**
	 * Get an attachment id with the same file used by the provided attachment id.
	 *
	 * @param int $id The attachement id.
	 */
	private function get_same_file_attachment_id( $id ) {
		global $wpdb;
		$attached_file = get_post_meta( $id, '_wp_attached_file', true );
		$other_attachment_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_attached_file' AND meta_value = %s AND post_id != %d",
				$attached_file,
				$id
			)
		);

		return $other_attachment_id;
	}

	/**
	 * Retrieves an array of post ids that have duplicate filenames/paths.
	 */
	private function get_shared_filename_ids() {

		global $wpdb;

		$sharedfile_ids = get_transient( 'mdd_sharedfile_ids' );

		if ( false === $sharedfile_ids ) {
			$sql = "SELECT DISTINCT p.post_id
				FROM $wpdb->postmeta AS p
				JOIN (
					SELECT count(*) AS sharedfile_count, meta_value
					FROM $wpdb->postmeta
					WHERE meta_key = '_wp_attached_file'
					GROUP BY meta_value
					HAVING sharedfile_count > 1
				) AS p2
				ON p.meta_value = p2.meta_value;";

			$sharedfile_ids = $wpdb->get_col( $sql );
			// If we don't do this, WP_Query's post__in gets an empty array and
			// returns all posts.
			if ( ! count( $sharedfile_ids ) ) {
				$sharedfile_ids = array( '0' );
			}
			set_transient( 'mdd_sharedfile_ids', $sharedfile_ids, HOUR_IN_SECONDS );
		}

		$this->shared_filename_ids = $sharedfile_ids;
		return $this->shared_filename_ids;

	}

	/**
	 * Clears out cached IDs and counts.
	 */
	static function delete_transients() {
		// Attachments that share hashes.
		delete_transient( 'mdd_duplicate_ids' );
		// Attachments that share files.
		delete_transient( 'mdd_sharedfile_ids' );
		// All attachments, period.
		delete_transient( 'mdd_count_all' );
		// All attachments with known hashes and sizes.
		delete_transient( 'mdd_count_indexed' );
		// All used attachmentss.
		delete_transient( 'mdd_count_unused' );
		// All duplicate attachments.
		delete_transient( 'mdd_count_duplicates' );
	}

	/**
	 * Process a bulk action performed on the media table.
	 */
	public function handle_bulk_actions() {

		// Get the current action.
		$doaction = $this->list_table->current_action();

		// Handle stock WP bulk actions (attach/detach).
		if ( 'detach' === $doaction ) {
			wp_media_attach_action( $_REQUEST['parent_post_id'], 'detach' );
		} elseif ( 'attach' === $doaction ) {
			wp_media_attach_action( $_REQUEST['found_post_id'] );
		} elseif ( 'smartdelete' !== $doaction && 'delete' !== $doaction ) {
			// Ignore any bulk actions other than attach, detach, delete, or smartdelete.
			return;
		}

		// Check nonce field. The type of request will determine which nonce field needs to be checked.
		if ( isset( $_REQUEST['post'] ) ) {

			// If the 'post' request variable is present, then this is a request to delete a single item.
			// Sanitize the post ID to operate on.
			$post_id = intval( $_REQUEST['post'] );

			// Check nonce field. This field is automatically generated for each "Delete Permanently" link
			// by WP_Media_List_Table.
			check_admin_referer( 'delete-post_' . $post_id );

			// Store the post ID in an array, so we can use the same foreach() loop we'd use if we were
			// performing a bulk action.
			$post_ids = array( $post_id );

		} else {

			// If the 'post' query var is absent, then this must be a bulk action request.
			// Check nonce field. This field is automatically generated for the Bulk Actions menu by
			// WP_Media_List_Table.
			check_admin_referer( 'bulk-media' );

			// Sanitize the list of post IDs to operate on.
			$post_ids = array_map( 'intval', $_REQUEST['media'] );
		}//end if

		// Redirect to the Media Deduper page by default.
		$redirect_url = add_query_arg(
			array(
				'page' => 'media-deduper',
			), 'upload.php'
		);

		switch ( $doaction ) {
			case 'smartdelete':
				// Loop over the array of record IDs and delete them.
				foreach ( $post_ids as $id ) {
					self::smart_delete_media( $id );
				}

				// Add query args that will cause Media_Deduper_Pro::admin_notices() to
				// show messages.
				$redirect_url = add_query_arg(
					array(
						'page' => 'media-deduper',
						'smartdeleted' => $this->smart_deleted_count . ',' . $this->smart_skipped_count,
					), $redirect_url
				);

				break;

			case 'delete':
				$deleted_count = 0;

				// Handle normal delete action.
				foreach ( $post_ids as $id ) {
					// Delete the attachment. See the note in smart_delete_media() about the reasons behind
					// the second argument ($force_delete).
					if ( wp_delete_post( $id, true ) ) {
						$deleted_count++;
					}
				}

				// Add query args that will cause Media_Deduper_Pro::admin_notices() to
				// show messages.
				$redirect_url = add_query_arg(
					array(
						'page' => 'media-deduper',
						'deleted' => $deleted_count,
					), $redirect_url
				);

				break;

			default:
				// Ignore any other actions.
				break;
		} //end switch

		// Redirect to the redirect URL set above.
		wp_redirect( $redirect_url );
		exit;
	}

	/**
	 * Declare the 'smartdeleted' query arg to be 'removable'.
	 *
	 * This causes users who visit upload.php?page=media-deduper&smartdeleted=1,0
	 * (which is where you're sent after 'smart-deleting' images) to only see
	 * upload.phpp?page=media-deduper in their URL bar.
	 *
	 * @param array $args An array of removable query args.
	 */
	public function removable_query_args( $args ) {
		$args[] = 'smartdeleted';
		return $args;
	}

	/**
	 * 'Smart-delete' an attachment post: delete only duplicate attachments, and replace references to
	 * deleted attachments.
	 *
	 * If there are no duplicates of the given attachment, this function will do nothing. If there
	 * are duplicates, then this function will check for references to the attachment and replace them
	 * with references to an older duplicate, and then delete the attachment.
	 *
	 * @param int $id The ID of the post to (maybe) delete.
	 */
	protected function smart_delete_media( $id ) {

		// Check whether there are other copies of this image.
		$this_post_hash = get_post_meta( $id, 'mdd_hash', true );
		if ( ! $this_post_hash ) {
			die( 'Something has gone horribly awry' );
		}
		$duplicate_media = new WP_Query(
			array(
				'ignore_sticky_posts' => true,
				'post__not_in'        => array( $id ),
				'post_type'           => 'attachment',
				'post_status'         => 'any',
				'orderby'             => 'ID',
				'order'               => 'ASC',
				'meta_key'            => 'mdd_hash',
				'meta_value'          => $this_post_hash,
			)
		);

		// If no other media with this hash was found, don't delete this media item. This way, even if
		// the user selects both images in a pair of duplicates, one will always be preserved.
		if ( ! $duplicate_media->have_posts() ) {
			$this->smart_skipped_count++;
			return;
		}

		// If this attachment is referenced anywhere on the site, replace references to it with
		// references to the duplicate with the lowest post ID.
		if ( $this->reference_handler->attachment_is_referenced( $id ) ) {
			$preserved_id = $duplicate_media->posts[0]->ID;
			$this->reference_handler->replace_all_references( $id, $preserved_id );
		}

		// Before deleting the attachment post, check whether this attachment's file is also used by
		// another attachment.
		$other_attachment_id = $this->get_same_file_attachment_id( $id );

		// If the file is in use by another attachment, use `__return_false()` on the `wp_delete_file`
		// hook to prevent file deletion -- unless some OTHER plugin has already done that.
		$is_blocking_file_deletion = false;
		if ( ! empty( $other_attachment_id ) && ! has_filter( 'wp_delete_file', '__return_false' ) ) {
			$is_blocking_file_deletion = true;
			add_filter( 'wp_delete_file', '__return_false' );
		}

		// Finally, delete this attachment. The second argument here ($force_delete) causes the
		// attachment to be deleted immediately, regardless of the user's MEDIA_TRASH setting. The
		// duplicates list table displays trashed and non-trashed attachments together, and allowing the
		// normal MEDIA_TRASH behavior would make bulk actions affect trashed and non-trashed
		// attachments differently: non-trashed attachments would be trashed rather than deleted, and
		// would continue to appear in the list table after being "deleted." in order to fully remove
		// them, the user would have to "delete" them a second time.
		if ( wp_delete_attachment( $id, true ) ) {
			$this->smart_deleted_count++;
		}

		// If we blocked deletion of files earlier, restore normal functionality. Note that since we're
		// checking the $is_blocking_file_deletion variable, `__return_false()` will only be removed if
		// MDD was responsible for adding it -- meaning this should not interfere with any other code
		// that prevents file deletion the same way.
		if ( $is_blocking_file_deletion ) {
			remove_filter( 'wp_delete_file', '__return_false' );
		}
	}

	/**
	 * Filters the media columns to add another one for filesize.
	 *
	 * @param array $posts_columns An array of column machine-readable names =>
	 *                             human-readable titles.
	 */
	public function media_columns( $posts_columns ) {
		$posts_columns['mdd_size'] = _x( 'Size', 'column name', 'media-deduper' );
		$posts_columns['mdd_used_in'] = _x( 'Used In', 'column name', 'media-deduper' );
		return $posts_columns;
	}

	/**
	 * Filters the media columns to make the Size column sortable.
	 *
	 * @param array $sortable_columns An array of sortable column machine readable
	 *                                names => human-readable titles.
	 */
	public function media_sortable_columns( $sortable_columns ) {
		$sortable_columns['mdd_size'] = array( 'mdd_size', true );
		$sortable_columns['mdd_used_in'] = array( 'mdd_referenced_by_count' );
		return $sortable_columns;
	}

	/**
	 * Handles the file size column output.
	 *
	 * @param string $column_name The machine-readable name of the column to
	 *                            display content for.
	 * @param int    $post_id     The ID of the post to display content for.
	 */
	public function media_custom_column( $column_name, $post_id ) {
		if ( 'mdd_size' === $column_name ) {
			$filesize = get_post_meta( $post_id, 'mdd_size', true );
			if ( ! $filesize ) {
				echo esc_html__( 'Unknown', 'media-deduper' );
			} else {
				echo esc_html( size_format( $filesize ) );
			}
		}

		if ( 'mdd_used_in' === $column_name ) {
			$references = ( ! empty( get_post_meta( $post_id, '_mdd_referenced_by', true ) ) ) ? (array) get_post_meta( $post_id, '_mdd_referenced_by', true ) : array();
			$post_ids = ( ! empty( $references ) ) ? array_keys( $references ) : array() ;
			if ( empty( $post_ids ) ) {
				echo esc_html__( 'None', 'media-deduper' );
			} elseif ( count( $post_ids ) === 1 && 0 !== absint( $post_ids[0] ) ) {
				$post_id = $post_ids[0];
				?>
				<a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a>
				<?php
			} else {
				echo sprintf( '%d content items', esc_html( count( $post_ids ) ) );
			}
		}
	}

	/**
	 * Change row action links in the Media Deduper list table. This is necessary because the normal
	 * row actions for a WP_Media_List_Table are different depending on the $is_trash property of the
	 * list table, which MDD_Media_List_Table ignores. These action links and their URLs are based on
	 * a selection of those in WP_Media_List_Table::_get_row_actions().
	 *
	 * @param array   $actions Row action links.
	 * @param WP_Post $post    The post being displayed.
	 */
	public function media_row_actions( $actions, $post ) {

		// Don't alter actions if we're not on the Media Deduper page.
		$screen = get_current_screen();
		if ( static::ADMIN_SCREEN !== $screen->base ) {
			return $actions;
		}

		// Initialize actions array (it's a little easier to reconstruct it entirely than to selectively
		// remove and insert actions).
		$actions = array();

		// Get the title or 'Auto Draft' text as used in the default row actions by
		// WP_Media_List_Table::handle_row_actions().
		$att_title = _draft_or_post_title();

		// If the user can edit this post, and it's not in the trash, show an edit link.
		if ( current_user_can( 'edit_post', $post->ID ) && 'trash' !== $post->post_status ) {
			$actions['edit'] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				get_edit_post_link( $post->ID ),
				/* translators: %s: attachment title */
				esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $att_title ) ),
				__( 'Edit' )
			);
		}

		if ( current_user_can( 'delete_post', $post->ID ) ) {

			// If this post is trashed, show a Restore from Trash link.
			if ( 'trash' === $post->post_status ) {
				$actions['untrash'] = sprintf(
					'<a href="%s" class="submitdelete aria-button-if-js" aria-label="%s">%s</a>',
					wp_nonce_url( "post.php?action=untrash&amp;post=$post->ID", 'untrash-post_' . $post->ID ),
					/* translators: %s: attachment title */
					esc_attr( sprintf( __( 'Restore &#8220;%s&#8221; from the Trash' ), $att_title ) ),
					__( 'Restore' )
				);
			}

			// If the user can delete this post, show a delete link.
			$actions['delete'] = sprintf(
				'<a href="%s" class="submitdelete aria-button-if-js" onclick="return showNotice.warn();" aria-label="%s">%s</a>',
				// Note: instead of linking to post.php here, like the standard WP media list table does, we
				// link to the MDD page so we can use our custom delete action handler, which ignores the
				// MEDIA_TRASH constant and always deletes the attachment in question.
				wp_nonce_url( "upload.php?page=media-deduper&amp;action=delete&amp;post=$post->ID", 'delete-post_' . $post->ID ),
				/* translators: %s: attachment title */
				esc_attr( sprintf( __( 'Delete &#8220;%s&#8221; permanently' ), $att_title ) ),
				__( 'Delete Permanently' )
			);
		}//end if

		// If this post isn't trashed, link to the single attachment template.
		if ( 'trash' !== $post->post_status ) {
			$actions['view'] = sprintf(
				'<a href="%s" aria-label="%s" rel="bookmark">%s</a>',
				get_permalink( $post->ID ),
				/* translators: %s: attachment title */
				esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $att_title ) ),
				__( 'View' )
			);
		}

		return $actions;
	}

	/**
	 * Filters the media columns to add another one for filesize.
	 *
	 * @param array $posts_columns An array of column machine-readable names =>
	 *                             human-readable titles.
	 */
	public function media_views( $views ) {

		$views = array(
			'all' => sprintf(
				'<a href="%s" class="%s">%s</a>',
				remove_query_arg( 'unused', admin_url( 'upload.php' ) ),
				( ( ( ! isset( $_GET['unused'] ) || empty( boolval( $_GET['unused'] ) ) ) && ( ! isset( $_GET['missing-alt-text'] ) || empty( boolval( $_GET['missing-alt-text'] ) ) ) ) ? 'current' : '' ),
				__( 'All', 'media-deduper' )
			),
			'unused' => sprintf(
				'<a href="%s" class="%s">%s</a>',
				add_query_arg( 'unused' , true, admin_url( 'upload.php' ) ),
				( isset( $_GET['unused'] ) && boolval( $_GET['unused'] ) ? 'current' : '' ),
				__( 'Unused', 'media-deduper' )
			),
			'missing-alt-text' => sprintf(
				'<a href="%s" class="%s">%s</a>',
				add_query_arg( 'missing-alt-text' , true, admin_url( 'upload.php' ) ),
				( isset( $_GET['missing-alt-text'] ) && boolval( $_GET['missing-alt-text'] ) ? 'current' : '' ),
				__( 'Missing Alt Text', 'media-deduper' )
			),
		);

		return $views;
	}

	/**
	 * Process an item in the bulk delete queue.
	 */
	public function mdd_as_test_action() {
		update_option( 'mdd_as_test', true );
	}

	/**
	 * Add meta query clauses corresponding to custom 'orderby' values.
	 *
	 * @param WP_Query $query A WP_Query object for which to alter query vars.
	 */
	public function pre_get_posts( $query ) {

		// Get the currently active tab.
		$active_tab = $this->active_tab();

		// Get the orderby query var.
		$orderby = $query->get( 'orderby' );

		// If there's only one orderby option, cast it as an array.
		if ( ! is_array( $orderby ) ) {
			$orderby = array(
				$orderby => $query->get( 'order' ),
			);
		}

		if ( in_array( 'mdd_size', array_keys( $orderby ), true ) ) {

			// Get the current meta query.
			$meta_query = $query->get( 'meta_query' );
			if ( ! $meta_query ) {
				$meta_query = array();
			}

			// Add a clause to sort by.
			$meta_query['mdd_size'] = array(
				'key'     => 'mdd_size',
				'type'    => 'NUMERIC',
				'compare' => 'EXISTS',
			);

			// Set the new meta query.
			$query->set( 'meta_query', $meta_query );
		}

		if ( in_array( 'mdd_referenced_by_count', array_keys( $orderby ), true ) ) {

			// Get the current meta query.
			$meta_query = $query->get( 'meta_query' );
			if ( ! $meta_query ) {
				$meta_query = array();
			}

			// Add a clause to sort by.
			$meta_query['mdd_referenced_by_count'] = array(
				'key' => '_mdd_referenced_by_count',
				'type' => 'NUMERIC',
				'compare' => 'EXISTS',
			);

			// Set the new meta query.
			$query->set( 'meta_query', $meta_query );
		}//end if

		// filter unused posts if passed
		if ( is_admin() && isset( $_REQUEST['unused'] ) && boolval( $_REQUEST['unused'] ) ) {
			// Get the current meta query.
			$meta_query = $query->get( 'meta_query' );
			if ( ! $meta_query ) {
				$meta_query = array();
			}

			// Add a clause to sort by.
			$meta_query['mdd_referenced_by_count'] = array(
				'key' => '_mdd_referenced_by_count',
				'value' => 0,
				'type' => 'NUMERIC',
				'compare' => '=',
			);

			// Set the new meta query.
			$query->set( 'meta_query', $meta_query );
		}

		// filter missing alt posts posts if passed
		if ( is_admin() && ( ( isset( $_REQUEST['missing-alt-text'] ) && boolval( $_REQUEST['missing-alt-text'] ) ) || ( 'alt-text' === $active_tab ) ) ) {
			// Get the current meta query.
			$meta_query = $query->get( 'meta_query' );
			if ( ! $meta_query ) {
				$meta_query = array();
			}

			// Add a clause to sort by.
			$meta_query['missing_alt_text'] = array(
				array(
					'relation' => 'OR',
					array(
						'key'     => '_wp_attachment_image_alt',
						'value'   => '',
						'compare' => '=',
					),
					array(
						'key'     => '_wp_attachment_image_alt',
						'compare' => 'NOT EXISTS',
					),
				),
				array(
					'key'     => '_wp_attachment_metadata',
					'value'   => 'image/',
					'compare' => 'LIKE',
				),
			);

			// Set the new meta query.
			$query->set( 'meta_query', $meta_query );
		}//end if
	}

	/**
	 * Include other functions
	 */
	protected function require_all( $dir, $depth = 0 ) {
		// strip slashes from end of string
		$dir = rtrim( $dir, '/\\' );
		// require all php files
		$scan = glob( $dir . DIRECTORY_SEPARATOR . '*' );
		foreach ( $scan as $path ) {
			if ( preg_match( '/\.php$|\.inc$/', $path ) ) {
				require_once $path;
			} elseif ( is_dir( $path ) ) {
				$this->require_all( $path, $depth + 1 );
			}
		}
	}
}


/**
 * Start up this plugin.
 */
function media_deduper_pro_init() {

	// If the free version of Media Deduper is active, prevent it from initializing itself.
	remove_action( 'init', 'media_deduper_init' );

	global $media_deduper_pro;
	$media_deduper_pro = new Media_Deduper_Pro();
}
// Add init function at an earlier priority than the free plugin's init function.
add_action( 'init', 'media_deduper_pro_init', 9 );
