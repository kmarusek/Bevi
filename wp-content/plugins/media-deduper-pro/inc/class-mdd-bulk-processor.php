<?php
/**
 * Media Deduper Pro: async bulk processing class.
 *
 * @package Media_Deduper_Pro
 */

/**
 * Indexer class.
 */
class MDD_Bulk_Processor {

	/**
	 * The namespace for options, etc.
	 *
	 * @var string
	 */
	protected $prefix = 'mdd_pro';

	/**
	 * A name for the specific action performed by this class.
	 *
	 * @var string
	 */
	protected $action = 'index';

	/**
	 * The array containing the item action to be attached to the async item action.
	 *
	 * @var array
	 */
	protected $item_action = array();

	/**
	 * The name of the WP option where we'll store bulk processor status data.
	 *
	 * @var string
	 */
	protected $status_option;

	/**
	 * The name of the WP option whose value will be set to 1 if the user stops the bulk processor.
	 *
	 * @var string
	 */
	protected $stop_option;

	/**
	 * Is the process stopped? If we check the 'stop' option and its value is 1, this will be set to
	 * TRUE so that we don't have to keep checking the stop option in the database.
	 *
	 * @var bool
	 */
	protected $is_stopped = false;

	/**
	 * Constructor. Adds hooks that watch for changes/access to the bulk processor status option.
	 */
	public function __construct( $action = 'index', $item_action = array() ) {
		if ( empty( $item_action ) ) {
			return;
		}
		// set up the bulk processor action.
		$this->action = $action;
		$this->item_action = $item_action;

		// Set option names.
		$this->status_option = "{$this->prefix}_{$action}_status";
		$this->stop_option = "{$this->prefix}_{$action}_stop";

		add_action( "mdd_{$action}_batch_action", array( $this, 'as_async_batch_action' ), 10, 1 );
		add_action( "mdd_{$action}_item_action", $item_action, 10, 1 );
	}

	/**
	 * Calulate the max number of ids allowed in a batch
	 * based on the latest post id length and AS restrictions
	 * see: https://github.com/woocommerce/action-scheduler/blob/master/classes/data-stores/ActionScheduler_DBStore.php#L13
	 *
	 * return int.
	 */
	public function get_max_bulk_process_batch_num() {
		// set a default.
		$bulk_process_batch_num = 1000;

		// grab any status or post type to ensure we get the biggest possible post id.
		$latest_post_args = array(
			'numberposts' => '1',
			'post_type'   => 'any',
			'post_status' => 'any',
			'orderby'     => 'ID',
			'order'       => 'DESC',
		);
		$latest_post = wp_get_recent_posts( $latest_post_args );

		$id = 0;

		if ( ! empty( $latest_post ) ) {
			$id = $latest_post[0]['ID'];
		}

		// 5 = [],"" in a json object.
		$json_characters = 5;

		// action scheduler max number of characters allowed in argument.
		$max_as_json_characters = 8000;

		$bulk_process_batch_num = floor( $max_as_json_characters / ( strlen( "$id" ) + $json_characters ) );

		return $bulk_process_batch_num;
	}

	/**
	 * Process the batch async process.
	 *
	 * @param array $batch The batch of ids.
	 */
	public function as_async_batch_action( $batch ) {
		// If the user has asked the bulk_process to stop, skip this.
		if ( $this->is_stopped() ) {
			return false;
		}
		foreach ( $batch as $post_id ) {
			as_enqueue_async_action( "mdd_{$this->action}_item_action", array( $post_id ), "mdd_{$this->action}_item" );
		}
	}

	/**
	 * Start the bulk process
	 *
	 * @param array $post_ids The array of ids to process.
	 */
	public function process( $post_ids ) {

		// Clear old bulk processor status data, if any.
		delete_option( $this->status_option );

		// Set & store total count.
		$status = $this->get_status();
		$status['total'] = count( $post_ids );
		$status['error_messages'] = array();
		$status['state'] = 'processing';
		$this->update_status( $status );

		// Initialize the 'stop' option. Note: we set the value to 0 instead of deleting the option
		// because WP caches unset options differently from options that are explicitly set, and
		// depending on what type of cache the site is using, we may need to clear the cache for this
		// option repeatedly.
		$this->update_option( $this->stop_option, 0 );

		/**
		 * Get the mdd bulk process batch number
		 * uses the get_max_bulk_process_batch_num function to calculate a safe number.
		 */
		$batches = array_chunk( $post_ids, apply_filters( "mdd_bulk_process_{$this->action}_batch_num", $this->get_max_bulk_process_batch_num() ) );

		// stop previously set actions. bulk process is starting over.
		as_unschedule_all_actions( "mdd_{$this->action}_batch_action" );
		as_unschedule_all_actions( "mdd_{$this->action}_item_action" );

		foreach ( $batches as $batch ) {
			as_enqueue_async_action( "mdd_{$this->action}_batch_action", array( $batch ), "mdd_{$this->action}_batch" );
		}
	}

	/**
	 * Check whether the bulk process is running.
	 */
	public function is_processing() {
		$processing = false;

		$pending_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => "mdd_{$this->action}_batch",
			), 'ids'
		);
		$pending_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_PENDING,
				'per_page' => -1,
				'group' => "mdd_{$this->action}_item",
			), 'ids'
		);

		$progress_batches = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => "mdd_{$this->action}_batch",
			), 'ids'
		);
		$progress_items = as_get_scheduled_actions(
			array(
				'status' => ActionScheduler_Store::STATUS_RUNNING,
				'per_page' => -1,
				'group' => "mdd_{$this->action}_item",
			), 'ids'
		);

		// if there pending actions we are processing.
		if ( ! empty( $pending_batches ) || ! empty( $pending_items ) || ! empty( $progress_batches ) || ! empty( $progress_items ) ) {
			$processing = true;
		}

		return $processing;
	}

	/**
	 * Get status data.
	 */
	public function get_status() {

		// Get raw data from option.
		$status = $this->get_option( $this->status_option );

		// If option was empty or something other than an array, initialize it.
		if ( ! $status || ! is_array( $status ) ) {
			$status = array();
		}

		// Make sure 'processed', 'failed', and 'total' are integers.
		$int_keys = array( 'processed', 'failed', 'total' );
		foreach ( $int_keys as $key ) {
			if ( ! isset( $status[ $key ] ) ) {
				$status[ $key ] = 0;
			} else {
				$status[ $key ] = (int) $status[ $key ];
			}
		}

		// Make sure 'error_messages' is an array.
		if ( ! isset( $status['error_messages'] ) || ! is_array( $status['error_messages'] ) ) {
			$status['error_messages'] = array();
		}

		// Make sure 'state' is a string.
		if ( ! isset( $status['state'] ) || empty( $status['state'] ) ) {
			$status['state'] = 'processing';
		}

		return $status;
	}

	/**
	 * Save updated status data.
	 *
	 * @param array $status The status data to save.
	 */
	public function update_status( $status ) {
		return $this->update_option( $this->status_option, $status );
	}

	/**
	 * Stop bulk processing now.
	 */
	public function stop() {

		// Signal the currently running background process to stop doing stuff.
		$this->update_option( $this->stop_option, 1 );

		// stop previously set actions.
		as_unschedule_all_actions( "mdd_{$this->action}_batch_action" );
		as_unschedule_all_actions( "mdd_{$this->action}_item_action" );

		$status = $this->get_status();

		$status['state'] = 'stopped';

		$this->update_status( $status );

		return $this;
	}

	/**
	 * Check whether the user has stopped the currently running bulk process.
	 *
	 * @return boolean TRUE if stopped, FALSE otherwise.
	 */
	public function is_stopped() {

		// Only check database if the `is_stopped` property is set to FALSE. If it's TRUE, then we don't
		// need to waste another DB query.
		if ( ! $this->is_stopped ) {

			if ( $this->get_option( $this->stop_option ) ) {
				$this->is_stopped = true;
			}
		}

		return $this->is_stopped;
	}

	/**
	 * Get the value of a WP option, making sure that the value is as 'fresh' as possible, i.e. not
	 * cached from an earlier call to get_option() if the value may have changed.
	 *
	 * @param string $option_name The name of the option to get.
	 */
	private function get_option( $option_name ) {

		$this->maybe_clear_option_cache( $option_name );

		return get_option( $option_name );
	}

	/**
	 * If the site is not using an object cache that's shared across requests, then clear the cache
	 * for the given option.
	 *
	 * @param string $option_name The name of the option for which to (maybe) clear the cache.
	 */
	private function maybe_clear_option_cache( $option_name ) {

		// Are we using an object cache that's shared across requests?
		global $_wp_using_ext_object_cache;

		// If not, then we'll need to clear the cache for the 'stopped' option, because another request
		// may have changed it and we need to be sure we're getting an up-to-date value.
		if ( ! $_wp_using_ext_object_cache ) {
			wp_cache_delete( $option_name, 'options' );
		}
	}

	/**
	 * Update a WP option. Disable autoloading so that options are cached individually.
	 *
	 * @param string $option_name  The name of the option to update.
	 * @param mixed  $option_value The new value for the option.
	 */
	private function update_option( $option_name, $option_value ) {
		// Disable autoloading.
		return update_option( $option_name, $option_value, false );
	}

	/**
	 * Fires once all items in the queue have been processed.
	 */
	public function complete() {

		// bail if we are stopped.
		if ( $this->is_stopped() ) {
			return $this;
		}

		// Update status with 'complete' state, unless current state is 'stopped'.
		$status = $this->get_status();
		if ( 'stopped' !== $status['state'] ) {
			// Clear any flags that indicate a need to re-run the bulk process.
			// MDD uses this for the indexer to flag when there is a need to re-index.
			update_option( "mdd_pro_re{$this->action}_updated", 0 );
			update_option( "mdd_pro_re{$this->action}_option_changed", 0 );
			$status['state'] = 'complete';
			$status['date']  = current_time( 'mysql', 0 );
			$status['date_gmt']  = current_time( 'mysql', 1 );
			$this->update_status( $status );
		}

		return $this;
	}
}
