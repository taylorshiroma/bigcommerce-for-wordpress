<?php


namespace BigCommerce\Settings\Screens;


use BigCommerce\Container\Settings;
use BigCommerce\Merchant\Account_Status;
use BigCommerce\Merchant\Onboarding_Api;

class Pending_Account_Screen extends Abstract_Screen {
	const NAME = 'bigcommerce_pending_account';

	protected function get_page_title() {
		return __( 'We’re Getting Your Account Ready', 'bigcommerce' );
	}

	protected function get_menu_title() {
		return __( 'Welcome', 'bigcommerce' );
	}

	protected function get_header() {
		$notices_placeholder = '<div class="wp-header-end"></div>'; // placeholder to tell WP where to put notices

		return sprintf(
			'%s<header class="bc-new-account__header"><img src="%s" alt="%s" /><h1 class="bc-settings-connect__title">%s</h1></header>',
			$notices_placeholder,
			trailingslashit( $this->assets_url ) . 'img/admin/big-commerce-logo.svg',
			__( 'BigCommerce', 'bigcommerce' ),
			__( 'We’re Getting Your Account Ready', 'bigcommerce' )
		);
	}

	public function render_settings_page() {
		$account_id = get_option( Onboarding_Api::ACCOUNT_ID, '' );
		if ( $account_id ) {
			$message = __( 'Bear with us just a moment while we finish creating your account.', 'bigcommerce' );
		} else {
			$message = __( 'Bear with us just a moment while we gather information about your account.', 'bigcommerce' );
		}
		ob_start();
		printf( '<div class="bc-welcome__account-connection">' );
		printf( '<h3 class="bc-welcome__pending-account-message"><i class="spinner bc-connect-spinner"></i> %s</h3>', $message );
		printf( '<div class="bc-welcome__account-connection-response" data-js="bc-welcome__account-connection-response"></div>' );

		if ( $account_id ) {
			printf( '<p class="bc-welcome__pending-account-instructions">%s</p>', __( 'You’ll receive an email to confirm your email address and set a password for your account.', 'bigcommerce' ) );
		}
		printf( '</div>' );

		/**
		 * Triggered before the settings screen form starts to render.
		 * The dynamic portion of the hook is the identifier of the settings screen.
		 *
		 * @param string $hook_suffix The hook suffix generated for the screen
		 */
		do_action( 'bigcommerce/settings/after_content/page=' . static::NAME, $this->hook_suffix );

		$content = ob_get_clean();

		printf( '<div class="wrap bc-settings bc-settings-%s">%s%s</div>', static::NAME, $this->get_header(), $content );
	}

	public function should_register() {
		return $this->configuration_status === Settings::STATUS_ACCOUNT_PENDING;
	}

}
