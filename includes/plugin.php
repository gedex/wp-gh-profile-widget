<?php

class GH_Profile_Widget_Plugin {

	/**
	 * Runs the plugin. Basically doing initialization
	 * and register callbacks to hooks.
	 *
	 * @param string               $path
	 * @param WP_GitHub_API_Plugin $api
	 */
	public function run( $path, $api ) {
		// Basic plugin information.
		$this->name    = 'gh_profile_widget'; // This maybe used to prefix options, slug of menu or page, and filters/actions.
		$this->version = '0.2.0';

		// Path.
		$this->plugin_path   = trailingslashit( plugin_dir_path( $path ) );
		$this->plugin_url    = trailingslashit( plugin_dir_url( $path ) );
		$this->includes_path = $this->plugin_path . trailingslashit( 'includes' );
		$this->views_path    = $this->plugin_path . trailingslashit( 'views' );
		$this->css_path      = $this->plugin_url  . trailingslashit( 'assets/css' );

		// Transient wrapper.
		require_once $this->includes_path . 'transient.php';
		$this->transient = new GH_Profile_Widget_Transient( $this );

		// GitHub API plugin that this plugins depends on.
		$this->api = $api;

		add_action( 'widgets_init', array( $this, 'register_widget' ) );

		add_action( 'init', array( $this, 'enqueue_style' ) );
	}

	/**
	 * Register the widget.
	 *
	 * @action widgets_init
	 */
	public function register_widget() {
		require_once $this->includes_path . 'widget.php';
		register_widget( 'GH_Profile_Widget' );
	}

	/**
	 * Enqueue style for the widget only if widget is active.
	 *
	 * @action init
	 */
	public function enqueue_style() {
		if ( is_active_widget( false, false, 'gh_profile_widget' ) && ! is_admin() ) {
			wp_enqueue_style( 'gh-octicons' );
			wp_enqueue_style( 'gh-profile-widget', $this->css_path . 'widget.css', false, $this->version, 'all' );
		}
	}
}
