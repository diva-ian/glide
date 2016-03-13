<?php

class WP_Glide {

	/**
	 * Base URL of the images.
	 *
	 * @var string
	 */
	private $base_url;

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->base_url = apply_filters( 'glide/base_url', '/img/' );
		$this->base_url = sprintf( '/%s/', ltrim( rtrim( $this->base_url, '/' ), '/' ) );

		add_action( 'init', [$this, 'add_endpoint'] );
		add_action( 'parse_query', [$this, 'handle_endpoint'] );
	}

	/**
	 * Add image endpoint.
	 */
	public function add_endpoint() {
		add_rewrite_tag( '%action%', '([^/]*)' );
		add_rewrite_rule( sprintf( '%s/([^/]*)/?', ltrim( $this->base_url, '/' ) ), 'index.php?action=$matches[1]', 'top' );
	}

	/**
	 * Handle image endpoint.
	 */
	public function handle_endpoint() {
		global $wp_query;

		if ( ! is_object( $wp_query ) ) {
			return;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( strpos( $_SERVER['REQUEST_URI'], $this->base_url ) === false ) {
			return;
		}

		$this->serve();
	}

	/**
	 * Serve images.
	 */
	private function serve() {
		$base = $this->base_url;
		$url  = $_SERVER['REQUEST_URI'];
		$url  = parse_url( $url );
		$path = str_replace( $base, '', $url['path'] );
		$path = ltrim( $path, '/' );

		// Docs: http://glide.thephpleague.com/1.0/config/setup/
		$options = apply_filters( 'glide/options', [
			'source'   => WP_CONTENT_DIR . '/uploads',
			'cache'    => WP_CONTENT_DIR . '/cache/glide',
			'base_url' => $base
		] );

		if ( file_exists( rtrim( $options['source'], '/' ) . '/' . $path ) ) {
			status_header( 200 );
			$server = \League\Glide\ServerFactory::create( $options );
			$server->outputImage( $path, $_GET );
			die;
		}

		status_header( 404 );
		echo 'Image not found.';
		die;
	}
}
