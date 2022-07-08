<?php

/*
 * Plugin Name: API
 * Description: Essential Functionality for the Website
 * Version: 1.0
 * Author: Diamadis Giorgos
 */

class API {
	private UI $ui;
	private Config $cfg;

	public function __construct() {
		add_action( 'rest_api_init', array( $this, "registerRestAPI" ) );

	}

	function registerRestAPI() {
		register_rest_route( 'slingo/v1', '/game/search', [
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, "api_searchGame" ),
		] );
		register_rest_route( 'slingo/v1', '/casino/search', [
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, "api_searchCasino" ),
		] );
		register_rest_route( 'slingo/v1', '/post/search', [
			'methods'  => WP_REST_Server::READABLE,
			'callback' => array( $this, "api_searchPost" ),
		] );
		register_rest_route( 'slingo/v1', '/casino/reorder', [
			'methods'  => WP_REST_Server::EDITABLE,
			'callback' => array( $this, "api_reOrderCasino" ),
		] );
	}

	function api_reOrderCasino( WP_REST_Request $request ): WP_REST_Response {
		$params = $request->get_body_params();

		$data = $params["data"];
		foreach ( $data as $d ) {
			update_post_meta( $d["casinoId"], "top_rated", $d["newOrder"] );
		}
		$response = new WP_REST_Response( "reOrdered!", 200 );
		$response->set_headers( [ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ] );

		return $response;
	}


	function api_searchPost( WP_REST_Request $request ): WP_REST_Response {

		$params       = $request->get_params();
		$page         = $params['page'] ?? 1;
		$type         = $params['type'] ?? "";
		$categoryName = $params['category'] ?? "";
		$paginate     = 0;

		if ( $type == 'post' || $type == 'bonus' || $type == 'payment' ) {
			$paginate = Container::Get( 'config' )->GetPostPagination();
		}
		if ( $type == 'game' ) {
			$paginate = Container::Get( 'config' )->GetGamePagination();

		}
		if ( $type == 'casino' ) {
			$paginate = Container::Get( 'config' )->GetCasinoPagination();

		}
		$args = array(
			'post_type'      => $type,
			'category_name'  => $categoryName,
			'post_status'    => 'publish',
			'posts_per_page' => $paginate,
			'paged'          => $page
		);

		$res         = [];
		$queryResult = new WP_Query( $args );
		$hp          = Container::Get( 'helper' );
		foreach ( $queryResult->posts as $post ) {
			$post              = json_decode( json_encode( $post ), true );
			$post['thumbnail'] = $hp->GetThumbnail( $post['ID'], 'archive-thumbnail' )[0];
			$post['permalink'] = get_permalink( $post['ID'] );
			$res[]             = $post;
		}

		$response = new WP_REST_Response( $res, 200 );
		$response->set_headers( [ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ] );


		return $response;

	}

	function api_searchGame( WP_REST_Request $request ): WP_REST_Response {
		global $wpdb;
		$params    = $request->get_params();
		$like      = $params['like'] ?? "";
		$page      = $params['page'] ?? 1;
		$not       = $params['games_not'] ?? "";
		$this->cfg = Container::Get( 'config' );

		$res  = [];
		$args = [];

		if ( $page > 0 || ( $page == 0 && $like != '' ) ) {
			$args = array(
				'post_type'      => 'game',
				'post_status'    => 'publish',
				'post__like'     => $like,
				'paged'          => $page,
				'posts_per_page' => $this->cfg->GetGamePagination(),
				'post__not_in'   => explode( ',', $not )
			);
		} else if ( $page == 0 && $like == '' ) {
			$args = array(
				'post_type'      => 'game',
				'post_status'    => 'publish',
				'post__like'     => $like,
				'paged'          => $page,
				'posts_per_page' => $this->cfg->GetGamePagination(),
				'post__in'       => explode( ',', $not )
			);
		} else if ( $page < 0 ) {
			$response = new WP_REST_Response( $res, 200 );
			$response->set_headers( [ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ] );

			return $response;
		}


		$hp          = Container::Get( 'helper' );
		$queryResult = new WP_Query( $args );


		foreach ( $queryResult->posts as $post ) {
//			$post->meta      = get_post_meta( $post->ID );
			$post->thumbnail = $hp->GetThumbnail( $post->ID, 'card-thumbnail' )[0];
			$post->permalink = get_permalink( $post->ID );
			$res[]           = $post;
		}

		$response = new WP_REST_Response( $res, 200 );
		$response->set_headers( [ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ] );

		return $response;
	}

	function api_searchCasino( WP_REST_Request $request ): WP_REST_Response {

		$params = $request->get_params();
		$page   = $params['page'] ?? 1;

		$bonuses = $params["bonuses"] ?? "";

		$payment_methods = $params["payment_methods"] ?? "";

		$games = $params["games"] ?? "";

		$meta_query = array();
		$count      = $params['count'] ?? Container::Get( 'config' )->GetCasinoPagination();

		$new = $params['new'] ?? null;
		if ( $new ) {
			$meta_query[] = array(
				'key'     => 'new_casino',
				'value'   => $new,
				'compare' => '==',
			);
		}

		if ( $payment_methods != "" ) {
			$meta_query[] = array(
				'key'     => 'payment',
				'value'   => $payment_methods,
				'compare' => '==',
			);
		}
		if ( $bonuses != "" ) {
			$meta_query[] = array(
				'key'     => 'bonus',
				'value'   => $bonuses,
				'compare' => '==',
			);
		}

		if ( $games != "" ) {
			$meta_query[] = array(
				'key'     => 'games',
				'value'   => $games,
				'compare' => 'IN',
			);
		}


		$args = array(
			'post_type'      => 'casino',
			'post_status'    => 'publish',
			'posts_per_page' => $count,
			'paged'          => $page,
			'meta_key'       => 'top_rated',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'relation'       => 'AND',
			'meta_query'     => $meta_query,
		);

		$query_result = new WP_Query( $args );
		$hp           = Container::Get( 'helper' );

		foreach ( $query_result->posts as $post ) {
			$post->meta              = get_post_meta( $post->ID );
			$rated                   = $post->meta['top_rated'][0];
			$post->meta['codeIcon']  = $hp->GetImage( 'copy.png' );
			$post->meta['arrowIcon'] = $hp->GetImage( 'arrowdown.png' );
			if ( $rated > 3 ) {
				$post->meta['ratedImage'] = "";
			} else {
				$post->meta['ratedImage'] = 'https://bestslingosites.co.uk/wp-content/uploads/2022/06/toprated ' . $rated . '.png';
			}

			$post->meta['permalink']  = get_permalink( $post->ID );
			$post->meta['thumbnail']  = $hp->GetThumbnail( $post->ID, 'casino-list-item' )[0];
			$post->meta['card_thumb'] = wp_get_attachment_image_src( get_post_meta( $post->ID, "img100", true ) );
			$post->meta['ratingNum']  = $hp->CalculateRating( $post->ID );
			$post->meta['star_full']  = $hp->GetImage( "starfilled.png" );
			$post->meta['star_empty'] = $hp->GetImage( "starempty.png" );
		}


		$response = new WP_REST_Response( $query_result->posts, 200 );
		$response->set_headers( [ 'Cache-Control' => 'must-revalidate, no-cache, no-store, private' ] );

		return $response;
	}

}

new API();
