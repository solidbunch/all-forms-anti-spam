<?php
namespace StarterKit\Controller;

use StarterKit\Helper\Utils;

/**
 * Front controller
 *
 * Controller which loading only on front (pages, posts etc)
 * contains all needed additional hooks,methods
 *
 * @category   Wordpress
 * @package    Starter Kit Backend
 * @author     SolidBunch
 * @link       https://solidbunch.com
 * @version    Release: 1.0.0
 * @since      Class available since Release 1.0.0
 */
class Front {

	/**
	 * Constructor - add all needed actions
	 *
	 * @return void
	 **/
	public function __construct() {

		// load assets
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		// remove default styles for Unyson Breadcrummbs
		add_action( 'wp_enqueue_scripts', array( $this, 'remove_assets' ), 99, 1 );
		add_action( 'wp_footer', array( $this, 'remove_assets' ) );

		// Anti-spam
		add_action( 'phpmailer_init', array( $this, 'antispam_form' ) );

	}

	/**
	 * Load JavaScript and CSS files in a front-end
	 *
	 * @return void
	 **/
	public function load_assets() {

		if ( $this->antispam_enabled() === 1 ) {
			wp_enqueue_script(
				'starter-kit-antispam',
				get_template_directory_uri() . '/assets/js/antispam.js',
				array(
					'jquery',
				),
				Starter_Kit()->config['cache_time'], true
			);
		}

	}

	/**
	 * Check if anti-spam enabled in theme options
	 *
	 * @return int
	 */
	public function antispam_enabled() {
		return (int) utils::get_option( 'forms_antispam', 0 );
	}

	/**
	 * @param \PHPMailer $phpmailer
	 *
	 * @return null|\PHPMailer
	 */
	public function antispam_form( \PHPMailer $phpmailer ) {

		if ( $this->antispam_enabled() !== 1 ) {
			return null;
		}

		if ( ! empty( $_POST ) && empty( $_POST['as_code'] ) ) {
			$phpmailer->clearAllRecipients();
		}

		return $phpmailer;
	}


}
