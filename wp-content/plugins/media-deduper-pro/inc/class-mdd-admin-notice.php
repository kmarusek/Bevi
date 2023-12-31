<?php
/**
 * MDD_Admin_Notice file.
 */

// Disable direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MDD_Admin_Notice' ) ) {
	/**
	 * MDD_Admin_Notice notice class, to easily handle admin notices
	 */
	class MDD_Admin_Notice {
		/**
		 * Notice text.
		 *
		 * @var $notice
		 */
		public $notice;
		/**
		 * Notice type.
		 *
		 * @var $type
		 */
		public $type;
		/**
		 * Notice actions.
		 *
		 * @var $actions
		 */
		public $actions;
		/**
		 * Constructor
		 *
		 * @param   string $notice the notice text.
		 * @param   string $type   the notice type.
		 */
		function __construct( $notice, $type = 'updated', $actions = '' ) {
			$this->notice   = $notice;
			$this->type     = $type;
			$this->actions  = $actions;
			add_action( 'admin_notices', array( &$this, 'add_admin_notice' ) );
		}
		/**
		 * Adds the admin notice
		 */
		function add_admin_notice() {
			echo '<div class="' . esc_attr( $this->type ) . '">';
			echo '<p>' . wp_kses_post( $this->notice ) . '</p>';
			echo wp_kses_post( $this->actions );
			echo '</div>';
		}
	}

}//end if
