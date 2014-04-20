<?php

class GH_Profile_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			strtolower( __CLASS__ ),
			__( 'GitHub Profile Widget', 'github-api' ),
			array(
				'description' => __( 'Show your GitHub profile.', 'github-api' ),
				'classname'   => strtolower( __CLASS__ ),
			)
		);
	}

	public function widget( $args, $instance ) {
		extract( $args );
		/**
		 * @var string $name
		 * @var string $id
		 * @var string $description
		 * @var string $class
		 * @var string $before_widget
		 * @var string $before_title
		 * @var string $widget_id
		 * @var string $widget_name
		 * @var string $after_widget
		 * @var string $after_title
		 */

		if ( empty( $instance['username'] ) ) {
			return;
		}

 		echo $before_widget;
 		echo $this->_get_view( $widget_id, $instance );
 		echo $after_widget;
	}

	/**
	 * Get view to render.
	 *
	 * @param  string $widget_id Widget ID
	 * @param  array  $instance  Widget instance
	 * @return string
	 */
	private function _get_view( $widget_id, array $instance ) {
		$plugin = $GLOBALS['gh_profile_widget'];

		$profile = $plugin->transient->get( $widget_id, array(
			'username' => $instance['username'],
		) );

		$theme = $instance['theme'] ? $instance['theme'] : 'dark-background';

		ob_start();
		require apply_filters( 'gh_profile_widget_view_path_for_widget', $plugin->views_path . 'widget.php' );

		return ob_get_clean();
	}

	public function update( $new_instance, $old_instance ) {
		$plugin = $GLOBALS['gh_profile_widget'];

		$new_instance['username'] = esc_html( $new_instance['username'] );

		$new_instance['theme'] = ! empty( $new_instance['theme'] ) ? $new_instance['theme'] : 'dark-background';
		if ( ! in_array( $new_instance['theme'], $this->get_themes() ) ) {
			$new_instance['theme'] = 'dark-background';
		}

		$plugin->transient->delete( $this->id );

		return $new_instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( $instance, array(
			'username' => '',
			'theme'    => 'dark-background',
		) );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('username') ); ?>"><?php _e( 'GitHub username', 'github-api' ); ?></label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id('username') ); ?>" name="<?php echo esc_attr( $this->get_field_name('username') ); ?>" value="<?php echo esc_attr( $instance['username'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('theme') ); ?>"><?php _e( 'Widget theme', 'github-api' ); ?></label>
			<br>
			<select id="<?php echo esc_attr( $this->get_field_id('theme') ); ?>" name="<?php echo esc_attr( $this->get_field_name('theme') ); ?>" value="<?php echo esc_attr( $instance['theme'] ); ?>">
			<?php foreach ( $this->get_themes() as $theme ) : ?>
				<option value="<?php echo esc_attr( $theme ); ?>" <?php selected( ( $theme === $instance['theme'] ) ); ?>><?php echo esc_html( $theme ); ?></option>
			<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function get_themes() {
		return apply_filters( 'gh_profile_widget_themes', array(
			'dark-background'  => 'dark-background',
			'light-background' => 'light-background',
		) );
	}
}
