<?php

class Casino {

	private UI $ui;
	private Helper $hp;
	private DB $db;
	private Config $cfg;

	public function __construct() {
		$this->ui  = Container::Get( 'ui' );
		$this->db  = Container::Get( 'db' );
		$this->cfg = Container::Get( 'config' );
		$this->hp  = Container::Get( 'helper' );

		add_filter( 'rwmb_meta_boxes', array( $this, "registerFields" ), 30 );
		add_shortcode( 'casinolist', array( $this, "CasinoList" ) );
		add_shortcode( 'casinotags', array( $this, "CasinoTags" ) );
	}

	function registerFields( $meta_boxes ) {
		$prefix = '';
		global $wpdb;
		$sql          = "select count(*) from {$wpdb->prefix}posts where post_type = 'casino' and post_status='publish'";
		$meta_boxes[] = [
			'title'   => esc_html__( 'Casino Fields', 'online-generator' ),
			'id'      => 'untitled',
			'context' => 'normal',
			'pages'   => 'casino',
			'fields'  => [
				[
					'type' => 'image_advanced',
					'name' => esc_html__( 'Image 100x100', 'online-generator' ),
					'id'   => $prefix . 'img100',
				],
				[
					'type' => 'textarea',
					'name' => esc_html__( 'About Casino Bonus', 'online-generator' ),
					'id'   => $prefix . 'about_cb',
				],
				[
					'type' => 'checkbox',
					'name' => esc_html__( 'New', 'online-generator' ),
					'id'   => $prefix . 'new_casino'
				],
				[
					'type' => 'textarea',
					'name' => esc_html__( 'Pros', 'online-generator' ),
					'id'   => $prefix . 'pros',
				],
				[
					'type' => 'textarea',
					'name' => esc_html__( 'Cons', 'online-generator' ),
					'id'   => $prefix . 'cons',
				],
				[
					'type' => 'textarea',
					'name' => esc_html__( 'Terms', 'online-generator' ),
					'id'   => $prefix . 'terms',
				],
				[
					'type'       => 'post',
					'name'       => esc_html__( 'Games', 'online-generator' ),
					'id'         => $prefix . 'games',
					'post_type'  => 'game',
					'field_type' => 'select_advanced',
					'multiple'   => true
				],
				[
					'type'       => 'post',
					'name'       => esc_html__( 'Bonus', 'online-generator' ),
					'id'         => $prefix . 'bonus',
					'post_type'  => 'bonus',
					'field_type' => 'select_advanced',
					'multiple'   => true
				],
				[
					'type'       => 'post',
					'name'       => esc_html__( 'Payment Method', 'online-generator' ),
					'id'         => $prefix . 'payment',
					'post_type'  => 'payment',
					'field_type' => 'select_advanced',
					'multiple'   => true
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'Title for shortcode', 'online-generator' ),
					'id'   => $prefix . 'sh_title'
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'Code', 'online-generator' ),
					'id'   => $prefix . 'code',
					'std'  => 'N/A',
				],
				[
					'type' => 'url',
					'name' => esc_html__( 'Affiliate Link', 'online-generator' ),
					'id'   => $prefix . 'affiliate_link',
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'Welcome Bonus', 'online-generator' ),
					'id'   => $prefix . 'welcome_bonus',
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'Expiry Period', 'online-generator' ),
					'id'   => $prefix . 'expiry_period',
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'Free Spins', 'online-generator' ),
					'id'   => $prefix . 'fr_spins',
				],
				[
					'type'       => 'taxonomy_advanced',
					'name'       => esc_html__( 'Wagering', 'online-generator' ),
					'id'         => $prefix . 'wagering_t',
					'taxonomy'   => 'wagering',
					'field_type' => 'select_advanced'
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'No Deposit Bonus', 'online-generator' ),
					'id'   => $prefix . 'no_deposit_bonus',
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'Max Winning', 'online-generator' ),
					'id'   => $prefix . 'max_winning',
				],
				[
					'type' => 'text',
					'name' => esc_html__( 'Max Bet', 'online-generator' ),
					'id'   => $prefix . 'max_bet',
				],

				[
					'type' => 'number',
					'name' => esc_html__( 'Readability', 'online-generator' ),
					'id'   => $prefix . 'readability',
				],
				[
					'type' => 'number',
					'name' => esc_html__( 'Customer Service', 'online-generator' ),
					'id'   => $prefix . 'customer_service',
				],
				[
					'type' => 'number',
					'name' => esc_html__( 'Games Rating', 'online-generator' ),
					'id'   => $prefix . 'games_rating',
				],
				[
					'type' => 'number',
					'name' => esc_html__( 'Banking', 'online-generator' ),
					'id'   => $prefix . 'banking',
				],
				[
					'type' => 'number',
					'name' => esc_html__( 'Bonuses', 'online-generator' ),
					'id'   => $prefix . 'bonuses',
				],
				[
					'type' => 'number',
					'name' => esc_html__( 'Mobile Experience', 'online-generator' ),
					'id'   => $prefix . 'mobile_experience',
				],
				[
					'type' => 'number',
					'name' => esc_html__( 'Top Rated', 'online-generator' ),
					'id'   => $prefix . 'top_rated',
					'std'  => $wpdb->get_var( $sql ) + 1
				],

			],
		];

		return $meta_boxes;
	}


	function CasinoList( $args ): string {
		$games           = $args['games'] ?? "";
		$payment_methods = $args['payment_methods'] ?? '';
		$bonuses         = $args['bonuses'] ?? "";
		$title           = $args['title'] ?? null;
		$type            = $args['type'] ?? "h2";

		$n = $args['n'] ?? $this->cfg->GetCasinoPagination();

		$meta_query = [];
		$filters    = [];
		$new        = $args['new'] ?? null;

		if ( $new == "Yes" ) {

			$isNew = $new == "Yes";

			$meta_query[] = [
				'key'     => 'new_casino',
				'value'   => $isNew ? 1 : 0,
				'compare' => '=='
			];

			$filters['new'] = $isNew ? 1 : 0;
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
			'posts_per_page' => - 1,
			'meta_key'       => 'top_rated',
			'orderby'        => 'meta_value_num',
			'order'          => 'ASC',
			'relation'       => 'AND',
			'meta_query'     => $meta_query,
		);

		$filters['payment_methods'] = $payment_methods;
		$filters['bonuses']         = $bonuses;
		$filters['games']           = $games;

		$activeFilters = json_encode( $filters );

		if ( $title ) {
			$header = do_shortcode( "[header title='$title' type='$type' link='false']" );
		} else {
			$header = "";
		}

		$adDisclosure = htmlspecialchars( $this->db->GetAdDisclosure(), ENT_QUOTES );

		$i_icon = $this->hp->GetImage( 'info_24px.png' );

		$res     = "$header
					<section>
					<div data-bs-toggle='tooltip' data-bs-html='true' data-container='body' data-bs-placement='bottom'
					             title='$adDisclosure'
					             style='float: right'
					             class='flex align-center'>
					            <img class='info-tooltip' src='$i_icon' width='20' height='20' alt=''>
					            <p style='margin: 0;font-size: .8rem;width: 72px'>Advertising Disclosure</p>
					</div>
					<div data-filter-page='1' active-filters='$activeFilters' class='casino-list m-auto w-100 row'>
					<div class='pt-4'></div>";
		$casinos = new WP_Query( $args );


		for ( $i = 0; $i < count( $casinos->posts ) && $i < $n; $i ++ ) {
			$res .= $this->ui->CasinoListItem( $casinos->posts[ $i ] );
		}
		$res .= "</div>
				<button onclick='loadMore(this,`casino`,`.casino-list`)'  class='button button-gray block m-auto mb-4'>Load more casinos
			        <div style='width: 1rem; height: 1rem;' class='spinner-border d-none' role='status'>
			            <span class='visually-hidden'>Loading...</span>
			        </div>
			    </button></section>
				";


		return $res;
	}

	function CasinoTags( $args ): string {
		$initGames = $args['ids'] ?? "aasdasd";
		$title     = $args['title'] ?? null;
		$args      = array(
			'post_type' => 'casino',
			'post__in'  => explode( ",", $initGames ),
		);


		$games = new WP_Query( $args );
// 		if ( $title ) {
// 			$header = do_shortcode( "[header title='$title' type='$type' link='false']" );
// 		} else {
// 			$header = "";
// 		}

		$res = "$title<div class='table'>";


		foreach ( $games->posts as $post ) {
			$res .= $this->ui->CasinoTags( $post );
		}

		$res .= "</div>";

		return $res;
	}
}

new Casino();
