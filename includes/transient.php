<?php
/**
 * Transient wrapper.
 */
class GH_Profile_Widget_Transient {

	/**
	 * Plugin instance.
	 *
	 * @var GH_Profile_Widget_Plugin
	 */
	private $plugin;

	public function __construct( GH_Profile_Widget_Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * @param string $id
	 * @param mixed  $value
	 */
	public function set( $id, $value ) {
		set_transient( $id, $value );
	}

	/**
	 * @param string $id     Widget ID
	 * @param array  $params Params to be passed to searcher
	 * @return mixed
	 */
	public function get( $id, array $params = array() ) {
		$profile = get_transient( $id );

		$is_valid = (
			! empty( $profile['timestamp'] )
			&&
			( time() - absint( $profile['timestamp'] ) < ( 60 * 60 * 24 ) ) // Makes sure profile is fresh enough.
		);

		if ( ! $is_valid ) {
			try {
				$username = $params['username'];
				if ( empty( $username ) ) {
					throw new Exception( __( 'Empty GitHub username', 'github-api' ) );
				}

				$profile = array(
					'timestamp' => time(),
					'data'      => $this->request( 'users/' . $username ),
				);

				// Unfortunately the returned data doesn't contain total starred.
				$starred = $this->request( 'users/' . $username . '/starred' );
				$profile['data']['starred'] = count( $starred );

				// Unfortunately the returned data doesn't contain organizations.
				$orgs = $this->request( 'users/' . $username . '/orgs' );
				$profile['data']['orgs'] = $orgs;

				// Replaces with non-api URL.
				foreach ( $profile['data']['orgs'] as $key => $org ) {
					if ( ! empty( $org['url'] ) ) {
						$profile['data']['orgs'][ $key ][ 'url' ] = str_replace( 'api.github.com/orgs', 'github.com', $org['url'] );
					}
				}

				// Replaces with non-api URL.
				$profile['data']['followers_url'] = trailingslashit( $profile['data']['html_url'] ) . 'followers';
				$profile['data']['following_url'] = trailingslashit( $profile['data']['html_url'] ) . 'following';
				$profile['data']['starred_url']   = 'https://github.com/stars/' . $username;

				$this->set( $id, $profile );

			} catch ( Exception $e ) {
				// @todo store and then show error message maybe?
				$this->delete( $id );
			}
		}

		if ( ! $profile ) {
			return null;
		}

		return $profile;
	}

	/**
	 * @param string $endpoint
	 * @param array  $params
	 *
	 * @return mixed
	 */
	public function request( $endpoint, array $params = array() ) {
		$resp   = $this->plugin->api->client->request( 'GET', $endpoint, $params );
		$status = intval( wp_remote_retrieve_response_code( $resp ) );

		if ( 200 === $status ) {
			return json_decode( wp_remote_retrieve_body( $resp ), true );
		} else {
			return null;
		}
	}

	/**
	 * @param string $id
	 */
	public function delete( $id ) {
		delete_transient( $id );
	}
}
