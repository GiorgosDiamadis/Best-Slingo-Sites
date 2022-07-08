<?php get_header();
require_once __DIR__ . "/classes/Container.php";
$ui        = Container::Get( 'ui' );
$hp        = Container::Get( 'helper' );
$initGames = get_post_meta( get_the_ID(), "cf_games" );

if ( $initGames == null ) {
	$games = [];
} else {
	$args = array(
		'post_type' => 'game',
		'post__in'  => $initGames,
	);

	$games = new WP_Query( $args );
}

$args = array(
	'post_type'      => 'payment',
	'post_status'    => 'publish',
	'posts_per_page' => '-1',
);

$payment = new WP_Query( $args );


$args = array(
	'post_type'      => 'bonus',
	'post_status'    => 'publish',
	'posts_per_page' => '-1',
);

$bonus = new WP_Query( $args );


?>

    <div class="container">
        <header class="flex justify-center align-center d-column">
            <div class="casino-finder" id="casinoFinder" style="margin-top: 5rem;margin-bottom: 5rem">
                <div class="steps hide-1000">
                    <button style="padding: 10px ;min-width: 170px"
                            class="button button-gray block m-auto mb-3 outlined ">Select Game
                    </button>
                    <button style="padding: 10px ;min-width: 170px" class="button button-gray block m-auto mb-3 ">
                        Select Bonus
                    </button>
                    <button style="padding: 10px;min-width: 170px" class="button button-gray block m-auto mb-3">
                        Payment Method
                    </button>
                </div>

                <div class="steps-mobile show-1000">
                    <h3>1.Select Game</h3>
                    <h3 class="d-none">2.Select Bonus</h3>
                    <h3 class="d-none">3.Select Payment Method</h3>
                </div>

                <div class="search" style="max-width: 200px">
                    <input class="form-control" id="search" placeholder="Search for a game">
                    <div style='width: 1rem; height: 1rem;' class='spinner-border d-none' role='status'>
                        <span class='visually-hidden'>Loading...</span>
                    </div>
                    <img src="https://bestslingosites.co.uk/wp-content/uploads/2022/06/search.png" alt="search icon">

                </div>
                <div id="step-1">
                    <div class="row mt-4 game-list" style="position: relative" data-page="0">
						<?php
						$gamesRes = "";
						foreach ( $games->posts as $game ) {
							$gamesRes .= $ui->GameUI( $game, false, false );
						}
						?>

						<?= $gamesRes ?>
                    </div>
                    <div class="row mt-4 game-search-list d-none" data-filter-page='1'>

                    </div>
                    <button id="loadmore" data-init-games="<?= implode( ",", $initGames ) ?>"
                            onclick="loadMore(this,'game','.game-list');"
                            style="padding: 5px 20px;margin-bottom: 0!important;"
                            class="button button-gray block m-auto mt-3">Load more
                        <div style='width: 1rem; height: 1rem;' class='spinner-border d-none' role='status'>
                            <span class='visually-hidden'>Loading...</span>
                        </div>
                    </button>
                    <button id="loadprevious" data-init-games="<?= implode( ",", $initGames ) ?>"
                            onclick="loadMore(this,'game','.game-list',false);"
                            style="padding: 5px 20px;margin-bottom: 0!important;"
                            class="button button-gray block m-auto mt-3">Load previous
                        <div style='width: 1rem; height: 1rem;' class='spinner-border d-none' role='status'>
                            <span class='visually-hidden'>Loading...</span>
                        </div>
                    </button>
                    <div class="buttons" style="text-align:end">
                        <button class="button button-orange" data-finder="next-step">Next step</button>
                        <button class="button button-orange" data-finder="find-casino"
                                onclick="findCasinos(this,'casino','.casino-search')">Find
                            Casino
                        </button>
                    </div>
                </div>
                <div class="d-none" id="step-2">
                    <div class="row mt-4">
						<?php
						$bonRes = "";
						foreach ( $bonus->posts as $bon ) {
							$bonRes .= $ui->ArticleUI( $bon, false, false );
						}
						?>
						<?= $bonRes ?>
                    </div>
                    <div class="buttons" style="text-align:end">
                        <button class="button button-orange" data-finder="back-step">Back</button>
                        <button class="button button-orange" data-finder="next-step">Next step</button>
                        <button class="button button-orange" data-finder="find-casino"
                                onclick="findCasinos(this,'casino','.casino-search')">Find
                            Casino
                        </button>
                    </div>

                </div>
                <div class="d-none" id="step-3">
                    <div class="row mt-4">
						<?php
						$paymentRes = "";
						foreach ( $payment->posts as $payment ) {
							$paymentRes .= $ui->ArticleUI( $payment, false, false );
						}
						?>
						<?= $paymentRes ?>
                    </div>
                    <div class="buttons" style="text-align:end">
                        <button class="button button-orange" data-finder="back-step">Back</button>
                        <button class="button button-orange" data-finder="find-casino"
                                onclick="findCasinos(this,'casino','.casino-search')">Find
                            Casino
                        </button>
                    </div>

                </div>
                <div class="d-none" id='cfResults'>
                    <div class="spinner-border d-none" id="cfSpinner" style="width: 5rem;
    height: 5rem;
    display: block;
    margin: auto;
    margin-top: 5rem;" role="status">
                        <span class="sr-only"></span>
                    </div>
                    <div class="row mt-4 casino-search" data-filter-page='0'>

                    </div>

                    <button id="seeall"
                            style="padding: 5px 20px;margin-bottom: 0!important;"
                            class="button button-gray block m-auto mt-3 hide-1000">
                        <a href="" style="color: rgb(112,112,112) !important;">
                            See all

                        </a>
                    </button>

                </div>
            </div>
        </header>
    </div>

<?php get_footer() ?>