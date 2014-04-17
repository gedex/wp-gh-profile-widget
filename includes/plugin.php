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
		$this->name    = 'search_tweets_widget'; // This maybe used to prefix options, slug of menu or page, and filters/actions.
		$this->version = '0.1.0';

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
}
