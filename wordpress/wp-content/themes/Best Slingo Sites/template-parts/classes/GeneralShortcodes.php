<?php
require_once __DIR__ . '/Container.php';

class General {
	private UI $ui;
	private Helper $hp;
	private Config $cfg;

	public function __construct() {
		add_shortcode( 'contentlink', [ $this, 'addContentLink' ] );
		add_shortcode( 'button', [ $this, 'button' ] );
		add_shortcode( 'section', [ $this, 'section' ] );
		add_shortcode( 'header', [ $this, 'mainHeader' ] );
		add_shortcode( 'rating', [ $this, 'rating' ] );
		add_shortcode( 'score', [ $this, 'score' ] );
		add_shortcode( 'archive', [ $this, 'archive' ] );
		add_shortcode( 'br', [ $this, 'br' ] );
		$this->ui  = Container::Get( 'ui' );
		$this->hp  = Container::Get( 'helper' );
		$this->cfg = Container::Get( 'config' );
	}

	public function archive( $args ): string {
		$type     = $args['type'] ?? '';
		$paginate = 0;
		$txt      = 'Load more';

		if ( $type == 'post' || $type == 'bonus' || $type == 'payment' ) {
			$paginate = $this->cfg->GetPostPagination();

			if ( $type == 'post' ) {
				$txt .= ' posts';
			}

			if ( $type == 'bonus' ) {
				$txt .= ' bonuses';
			}

			if ( $type == 'payment' ) {
				$txt .= ' payment methods';
			}

		}
		if ( $type == 'game' ) {
			$paginate = $this->cfg->GetGamePagination();
			$txt      .= ' games';
		}
		if ( $type == 'casino' ) {
			$paginate = $this->cfg->GetCasinoPagination();
			$txt      .= ' casinos';

		}
		$args   = [
			'post_type'      => $type,
			'posts_per_page' => $paginate,
			'post_status'    => 'publish',
		];
		$uiFunc = function ( $data ) {
			return $this->ui->Archive( $data );
		};
		if ( count( $_GET ) > 0 ) {
			$meta_query = [];
			$games      = $_GET['games'] ?? null;
			$bonuses    = $_GET['bonuses'] ?? null;
			$payments   = $_GET['payment_methods'] ?? null;

			$args['posts_per_page'] = - 1;
			$args['meta_key']       = 'top_rated';
			$args['orderby']        = 'meta_value_num';
			$args['order']          = 'ASC';

			if ( $payments ) {

				$meta_query[] = [
					'key'     => 'payment',
					'value'   => $payments,
					'compare' => '==',
				];
			}

			if ( $games ) {

				$meta_query[] = [
					'key'     => 'games',
					'value'   => $games,
					'compare' => '==',
				];
			}

			if ( $bonuses ) {

				$meta_query[] = [
					'key'     => 'bonus',
					'value'   => $bonuses,
					'compare' => '==',
				];
			}

			$args['meta_query'] = $meta_query;


//			var_dump( $args );
			$uiFunc = function ( $data ) {
				return $this->ui->CasinoListItem( $data );
			};
		}

		$archives = new WP_Query( $args );

		$filters = json_encode( [ 'type' => $type ] );
		$res     = "<div class='articles casino-list' data-page='1' data-filter-page='1' active-filters='$filters'>";
		foreach ( $archives->posts as $archive ) {
			$res .= $uiFunc( $archive );
		}

		$res .= '</div>';
		if ( count( $_GET ) <= 0 ) {
			$res .= "<button onclick='loadMore(this,`post`,`.articles`)' class='button button-gray block m-auto mb-4'>$txt
        <div style='width: 1rem; height: 1rem;' class='spinner-border d-none' role='status'>
            <span class='visually-hidden'>Loading...</span>
        </div>
    </button>";
		}


		return $res;
	}

	public function br( $args ): string {
		return '<br>';
	}

	public function rating( $args ): string {
		$title = $args['title'];
		$n     = $args['stars'];
		$stars = $this->ui->Stars( $n );
		$score = $this->hp->CalculateRating( get_the_ID() );


		return " <div class='rating-container'  style='display:flex;align-items:center;gap: 15px'><h5 style='display: inline-block;margin-right: 2rem'>$title rating</h5>
                    $stars <strong>( $n/10 )</strong></div>";
	}

	public function score( $args ): string {
		$n     = $this->hp->CalculateRating( get_the_ID() );
		$stars = $this->ui->Stars( $n );


		return " <div style='display: flex;align-items: center;gap: 30px'>$stars <strong>( $n/10 )</strong></div> ";
	}

	public function addContentLink( $args ): string {
		$title = $args['title'];

		return "<h3 class='content-link-item'>$title</h3>";
	}

	public function mainHeader( $args ): string {
		$title  = $args['title'];
		$type   = $args['type'];
		$isLink = $args['link'] ?? 'true';

		$isLink = $isLink == 'true';

		$class = $isLink ? 'content-link-title' : '';

		$ret = '';
		if ( $type == 'h1' ) {
			$ret = "<h1 class='main-header $class '>$title</h1>";
		} else if ( $type == 'h2' ) {
			$ret = "<h2 class='main-header $class'>$title</h2>";
		} else if ( $type == 'h3' ) {
			$ret = "<h3 class='main-header $class'>$title</h3>";
		} else if ( $type == 'h4' ) {
			$ret = "<h4 class='main-header $class'>$title</h4>";
		} else if ( $type == 'h5' ) {
			$ret = "<h5 class='main-header $class'>$title</h5>";
		} else {
			$ret = "<h6 class='main-header $class'>$title</h6>";
		}

		return $ret;
	}

	public function button( $args ): string {

		$nofollow = $args['nofollow'] ?? 'no';
		$text     = $args['title'] ?? '';
		$link     = $args['link'] ?? '';
		$a        = "<a style='text-decoration:none;'";
		if ( $nofollow == 'Yes' ) {
			$a .= " target='_blank' rel='nofollow' href='$link'>";
		} else {
			$a .= " target='_self' rel='next' href='$link'>";

		}
		$a .= " <button class='button button-orange'> $text</button></a>";

		return $a;
	}

	public function section( $args, $content = null ): string {

		$bg    = $args['bg'] ?? null;
		$title = $args['title'] ?? null;
		$ids   = $args['games'] ?? null;
		$type  = $args['type'] ?? 'h2';


		if ( $content != null ) {
			$content = do_shortcode( $content );
			$content = htmlspecialchars_decode( $content );
		}

		$uiFunc = null;

		if ( $ids != null ) {
			$uiFunc = function ( $data ) {
				return $this->ui->GameUI( $data );
			};

			$args = [
				'post_type'   => 'game',
				'post_status' => 'publish',
			];


			if ( $ids != 'all' ) {
				$args['post__in'] = explode( ',', $ids );
			}

		}
		if ( $ids == null ) {
			$ids = $args['casinos'] ?? null;
			if ( $ids != null ) {
				$args = [
					'post_type'   => [ 'casino' ],
					'post_status' => 'publish',

				];
				if ( $ids != 'all' ) {
					$ids              = explode( ',', $ids );
					$args['post__in'] = $ids;
				}
				if ( is_array( $ids ) && count( $ids ) == 1 ) {
					$uiFunc = function ( $data ) {
						return $this->ui->CasinoCard( $data, true );
					};
				} else {
					$uiFunc = function ( $data ) {
						return $this->ui->CasinoCard( $data );
					};
				}
			}

		}


		if ( $ids == null ) {
			$ids = $args['articles'] ?? null;
			if ( $ids != null ) {
				$uiFunc = function ( $data ) {
					return $this->ui->ArticleUI( $data );
				};
				$args   = [
					'post_type'   => [ 'post', 'bonus', 'payment', 'page' ],
					'post_status' => 'publish',

				];

				if ( $ids != 'all' ) {
					$args['post__in'] = explode( ',', $ids );
				}
			}

		}


		$queryResult = new WP_Query( $args );

		if ( $bg == 'grey' ) {
			$class = 'section-grey';
		} else if ( $bg == 'dark-blue' ) {
			$class = 'section-bg-blue';
		} else {
			$class = '';
		}

		$uiRes = '';

		if ( count( $queryResult->posts ) !== 0 ) {
			foreach ( $queryResult->posts as $result ) {
				$uiRes .= $uiFunc( $result );
			}
		}

		if ( $title !== null ) {
			$title = $this->mainHeader( [ 'title' => $title, 'type' => $type, 'link' => 'false' ] );
		} else {
			$title = '';
		}

		ob_start();
		?>
        <section class='<?= $class ?>'>
            <!--            <div class='pt-2'></div>-->
			<?= $title ?>
            <div class='section-content'>
				<?= $content ?>
            </div>
            <div class='row mt-4'>
				<?= $uiRes ?>
            </div>
            <div class='pt-4'></div>
        </section>
		<?php

		return ob_get_clean();
	}
}


new General();