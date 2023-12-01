<?php
/**
 * Astra Pro Sites
 *
 * @since 1.0.0
 * @package Astra Pro Sites
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Astra_Pro_Sites' ) ) :

	/**
	 * Astra Pro Sites
	 *
	 * @since 1.0.0
	 */
	class Astra_Pro_Sites {

		/**
		 * Instance of Astra_Pro_Sites
		 *
		 * @since 1.0.0
		 * @var object class object.
		 */
		private static $instance = null;

		/**
		 * Instance of Astra_Pro_Sites.
		 *
		 * @since 1.0.0
		 *
		 * @return object Class object.
		 */
		public static function set_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {

			self::includes();

			add_action( 'admin_init', array( $this, 'admin_notices' ), 1 );
			add_action( 'load-index.php', array( $this, 'admin_dashboard_notices' ) );
			add_action( 'astra_notice_before_markup', array( $this, 'notice_assets' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_filter( 'astra_sites_localize_vars', array( $this, 'update_vars' ) );
			add_filter( 'astra_sites_render_localize_vars', array( $this, 'update_vars' ) );
			add_filter( 'astra_sites_api_params', array( $this, 'api_request_params' ) );
			add_filter( 'astra_sites_menu_page_title', array( $this, 'page_title' ) );

		}

		/**
		 * Include Files.
		 *
		 * @since 1.0.7
		 */
		private static function includes() {
			require_once ASTRA_PRO_SITES_DIR . 'classes/class-astra-pro-sites-update.php';
			require_once ASTRA_PRO_SITES_DIR . 'classes/class-astra-pro-sites-white-label.php';
		}

		/**
		 * API Request Params
		 *
		 * @since 1.0.5
		 *
		 * @param  array $args API request arguments.
		 * @return arrray       Filtered API request params.
		 */
		public function api_request_params( $args = array() ) {

			$args['site_url']     = site_url();
			$args['purchase_key'] = Astra_Sites::get_instance()->get_license_key();

			return $args;
		}

		/**
		 * Page Title
		 *
		 * @since 1.0.0
		 *
		 * @param  string $title Page Title.
		 * @return string        Filtered Page Title.
		 */
		public function page_title( $title = '' ) {
			return Astra_Pro_Sites_White_Label::get_option( 'astra-sites', 'name', ASTRA_SITES_NAME );
		}

		/**
		 * Update Vars
		 *
		 * @since 1.0.0
		 *
		 * @param  array $vars Localize variables.
		 * @return array        Filtered localize variables.
		 */
		public function update_vars( $vars = array() ) {

			$vars['getProText'] = __( 'Get Access!', 'astra-sites' );
			$vars['getProURL']  = admin_url( 'plugins.php?bsf-inline-license-form=astra-pro-sites' );

			return $vars;
		}

		/**
		 * Loads textdomain for the plugin.
		 *
		 * @since 1.0.0
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'astra-sites' );
		}

		/**
		 * Admin Notices
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function admin_notices() {

			Astra_Notices::add_notice(
				array(
					'type'    => 'error',
					'class'   => 'astra-sites-notice',
					'show_if' => ( is_plugin_active( 'astra-sites/astra-sites.php' ) ) ? true : false,
					/* translators: %1$s white label plugin name and %2$s deactivation link */
					'message' => sprintf( __( 'You have two versions of the %1$s activated, click here to&nbsp;<a href="%2$s">Deactivate one</a>.', 'astra-sites' ), Astra_Pro_Sites_White_Label::get_option( 'astra-sites', 'name', ASTRA_SITES_NAME ), esc_url( $this->deactivation_link() ) ),
				)
			);

			add_action( 'plugin_action_links_' . ASTRA_PRO_SITES_BASE, array( $this, 'action_links' ) );
		}

		/**
		 * Admin Dashboard Notices.
		 *
		 * @since 3.1.17
		 * @return void
		 */
		public function admin_dashboard_notices() {
			if ( ! is_plugin_active( 'astra-sites/astra-sites.php' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_welcome_notices' ) );
			}
		}

		/**
		 * Admin Welcome Notice.
		 *
		 * @since 3.1.17
		 * @return void
		 */
		public function admin_welcome_notices() {
			$first_import_status = get_option( 'astra_sites_import_complete', false );
			Astra_Notices::add_notice(
				array(
					'id'      => 'astra-sites-welcome-notice',
					'type'    => 'notice',
					'class'   => 'astra-sites-welcome',
					'show_if' => ( false === Astra_Sites_White_Label::get_instance()->is_white_labeled() && empty( $first_import_status ) ),
					/* translators: %1$s white label plugin name and %2$s deactivation link */
					'message' => sprintf(
						'<div class="notice-welcome-container">	
							<div class="text-section">
								<h1 class="text-heading">' . __( 'Welcome to Starter Templates!', 'astra-sites' ) . '</h1>
								<p>' . __( 'Create professionally designed pixel-perfect websites in minutes.', 'astra-sites' ) . '</p>
								<a href="/wp-admin/themes.php?page=starter-templates" class="text-button">' . __( 'Get Started', 'astra-sites' ) . '</a>
							</div>
							<div class="showcase-section">
								<img src="' . esc_url( ASTRA_SITES_URI . 'inc/assets/images/templates-showcase.png' ) . '" />
							</div>
						</div>
						<div class="notice-content-container">
							<div class="content-section">
								<div class="icon-section">
								<img src="' . esc_url( ASTRA_SITES_URI . 'inc/assets/images/dashicons-cart.svg' ) . '" /></div>
								<div class="link-section">
									<h4>' . __( 'Ecommerce', 'astra-sites' ) . '</h4>
									<p>' . __( 'Looking for a fully operational eCommerce template to launch a store or level up an existing one?', 'astra-sites' ) . '</p>
									<a href="/wp-admin/themes.php?page=starter-templates&ci=2&s=E-Commerce">' . __( 'View Ecommerce Templates', 'astra-sites' ) . ' →</a>
								</div>
							</div>
							<div class="content-section">
								<div class="icon-section">
								<img src="' . esc_url( ASTRA_SITES_URI . 'inc/assets/images/dashicons-building.svg' ) . '" /></div>
								<div class="link-section">
									<h4>' . __( 'Local Business', 'astra-sites' ) . '</h4>
									<p>' . __( 'Fully customizable local business templates that can deliver a fully functioning website in minutes', 'astra-sites' ) . '</p>
									<a href="/wp-admin/themes.php?page=starter-templates&ci=2&s=Business">' . __( 'View Local Business Templates', 'astra-sites' ) . ' →</a>
								</div>
							</div>
							<div class="content-section">
								<div class="icon-section">
								<img src="' . esc_url( ASTRA_SITES_URI . 'inc/assets/images/dashicons-megaphone.svg' ) . '" /></div>
								<div class="link-section">
									<h4>' . __( 'Agency', 'astra-sites' ) . '</h4>
									<p>' . __( 'Do more in less time with Starter Templates. Pro-quality designs that can be fully customized to suit your clients.', 'astra-sites' ) . '</p>
									<a href="/wp-admin/themes.php?page=starter-templates&ci=2&s=Agency">' . __( 'View Agency Templates', 'astra-sites' ) . ' →</a>
								</div>
							</div>
							<div class="content-section">
								<div class="icon-section">
								<img src="' . esc_url( ASTRA_SITES_URI . 'inc/assets/images/dashicons-welcome-write-blog.svg' ) . '" /></div>
								<div class="link-section">
									<h4>' . __( 'Blog', 'astra-sites' ) . '</h4>
									<p>' . __( 'Customizable blog templates covering every niche. Page builder compatible, easy to use and fast!', 'astra-sites' ) . '</p>
									<a href="/wp-admin/themes.php?page=starter-templates&ci=2&s=Blog">' . __( 'View Blog Templates', 'astra-sites' ) . ' →</a>
								</div>
							</div>
						</div>'
					),
				)
			);
		}

		/**
		 * Plugin Deactivation Link
		 *
		 * @since 1.0.0
		 *
		 * @param  string $slug Plugin Slug.
		 * @return string       Plugin Deactivation Link.
		 */
		private function deactivation_link( $slug = 'astra-sites' ) {

			$deactivate_url = admin_url( 'plugins.php' );
			if ( is_plugin_active_for_network( ASTRA_SITES_BASE ) ) {
				$deactivate_url = network_admin_url( 'plugins.php' );
			}
			return add_query_arg(
				array(
					'action'        => 'deactivate',
					'plugin'        => rawurlencode( $slug . '/' . $slug . '.php' ),
					'plugin_status' => 'all',
					'paged'         => '1',
					'_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $slug . '/' . $slug . '.php' ),
				),
				$deactivate_url
			);
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @since 1.0.0
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array        Filtered plugin action links.
		 */
		public function action_links( $links = array() ) {

			if ( is_plugin_active( 'astra-sites/astra-sites.php' ) ) {
				return $links;
			}

			$arguments = array(
				'page' => 'starter-templates',
			);

			$url = add_query_arg( $arguments, admin_url( 'themes.php' ) );

			$action_links = array(
				'settings' => '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr__( 'See Library', 'astra-sites' ) . '">' . esc_html__( 'See Library', 'astra-sites' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Enqueue Astra Notices CSS.
		 *
		 * @since 3.1.17
		 *
		 * @return void
		 */
		public static function notice_assets() {
			$file = 'astra-notices.css';
			wp_enqueue_style( 'astra-sites-notices', ASTRA_SITES_URI . 'assets/css/' . $file, array(), ASTRA_SITES_VER );
		}

	}

	/**
	 * Kicking this off by calling 'set_instance()' method
	 */
	Astra_Pro_Sites::set_instance();

endif;
