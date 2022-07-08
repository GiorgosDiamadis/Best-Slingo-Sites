<?php
get_header();
$db           = Container::Get( 'db' );
$ui           = Container::Get( 'ui' );
$hp           = Container::Get( 'helper' );
$adDisclosure = $db->GetAdDisclosure();
$meta         = get_post_meta( get_the_ID() );
$payments     = $db->GetEntries( 'payment' );
$bonuses      = $db->GetEntries( 'bonus' );
$faqBuilder   = Container::Get( 'faqBuilder' );

$sizes = $hp->GetImageUrls( get_the_ID() );
$alt   = get_the_title() . " thumbnail ";
?>
    <div class="container" data-game-id="<?= get_the_ID() ?>">
        <header class="justify-center align-center">
            <nav class="show-1000" style="display: none;--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a style="color: white" href="<?= get_home_url() ?>">Home</a></li>
                    <li class="breadcrumb-item"><a style="color: white"
                                                   href="<?= get_home_url() . "/free-slingo-games" ?>">Free Slingo
                            Games</a>
                    </li>
                    <li class="breadcrumb-item active-bread" style="color: white"
                        aria-current="page"><?= get_the_title() ?>
                    </li>
                </ol>
            </nav>
            <div class="game-single">
                <div data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?= $adDisclosure ?>"
                     class="flex align-center tooltip-casino-single hide-1000">
                    <img src="<?= $hp->GetImage( "info_24px.png" ) ?>" width="20" height="20" alt="">
                    <p style="margin: 0;font-size: .8rem;width: 72px">Advertising Disclosure</p>
                </div>
                <nav class="hide-1000" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= get_home_url() ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= get_home_url() . "/free-slingo-games" ?>">Free Slingo
                                Games</a></li>
                        <li class="breadcrumb-item active-bread" aria-current="page"><?= get_the_title() ?></li>
                    </ol>
                </nav>

                <h1 style="margin: 0"><?= get_the_title() ?></h1>
                <div class="row mt-3">
                    <div class="col-12 col-lg-6">

                        <picture>
                            <source media="(max-width:600px)" srcset="<?= $sizes['review-small'] ?>">
                            <source media="(max-width:1200px)" srcset="<?= $sizes['review-medium'] ?>">
                            <source media="(max-width:1400px)" srcset="<?= $sizes['review-large'] ?>">
                            <img class="w-100" src=
                            "<?= $sizes['review-large'] ?>"
                                 alt="<?= $alt ?>">
                        </picture>

                    </div>
                    <div class="col-12 col-lg-6 mt-2">
                        <p>
							<?= do_shortcode( get_the_excerpt() ) ?>
                        </p>
                        <div class="buttons mt-3 " style="text-align: end">
                            <button class="button button-green mt-3"><a rel="nofollow" target="_blank"
                                                                        href="<?= $meta['affiliate_link'][0] ?? '' ?>">Play
                                    with real
                                    money</a></button>
                            <button data-bs-toggle="modal"
                                    onclick="load()" data-bs-target="#exampleModal"
                                    class="button button-outline mt-3  ">Try demo
                            </button>

                        </div>
                    </div>

                </div>


            </div>
        </header>


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" style="height: 50vh">
                <div class="modal-content" style="height: 100%">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Game Demo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe src="<?= $meta['game_demo'][0] ?? '' ?>" loading="lazy" width="100%"
                                height="100%"></iframe>
                    </div>
                    <div class="modal-footer">
                        <a href="<?= $meta['affiliate_link'][0] ?? "" ?>">

                        </a>
                        <button class="button button-green"><a style="margin-top: 0" rel="nofollow" target="_blank"
                                                               href="<?= $meta['affiliate_link'][0] ?? '' ?>">Play
                                with real
                                money</a></button>
                        <button type="button" onclick="unload()"
                                class="button button-orange" data-bs-dismiss="modal">Close game
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--        -->
        <section class="section-grey ">
            <div class="pb-5"></div>
            <h3 class="main-header" style="margin: 0">Overview</h3>
            <div class="row game-details mt-5">
                <div class="col-lg-4 col-6 flex mb-5" style="gap: 10px">
                    <img width="44" height="42" src="<?= $hp->GetImage( "theme.png" ) ?>" alt="theme icon">
                    <div class="flex d-column game-features">
                        <p class="bold">Theme </p>
                        <p><?= $meta['theme'][0] ?? "" ?></p>
                    </div>

                </div>

                <div class="col-lg-4 col-6 flex mb-5" style="gap: 10px">
                    <img width="44" height="42" src="<?= $hp->GetImage( "money.png" ) ?>" alt="max bet icon">
                    <div class="flex d-column game-features">
                        <p class="bold">Max Bet </p>
                        <p><?= $meta['max_bet'][0] ?? "" ?></p>
                    </div>

                </div>
                <div class="col-lg-4 col-6 flex mb-5" style="gap: 10px">
                    <img width="44" height="42" src="<?= $hp->GetImage( "gift.png" ) ?>" alt="free spins icon">
                    <div class="flex d-column  game-features">
                        <p class="bold">Starting Spins </p>
                        <p><?= $meta['free_spins'][0] ?? "" ?></p>
                    </div>

                </div>
                <div class="col-lg-4 col-6 flex mb-5" style="gap: 10px">
                    <img width="44" height="42" src="<?= $hp->GetImage( "growth.png" ) ?>" alt="volatility icon">
                    <div class="flex d-column  game-features">
                        <p class="bold">Volatility </p>
                        <p><?= $meta['volatility'][0] ?? "" ?></p>
                    </div>

                </div>
                <div class="col-lg-4 col-6 flex mb-5" style="gap: 10px">
                    <img width="44" height="42" src="<?= $hp->GetImage( "happy.png" ) ?>" alt="extra spins icon">
                    <div class="flex d-column game-features ">
                        <p class="bold">Extra Spins </p>
                        <p><?= $meta['bonus_feature'][0] ?? "" ?></p>
                    </div>

                </div>
                <div class="col-lg-4 col-6 flex mb-5" style="gap: 10px">
                    <img width="44" height="42" src="<?= $hp->GetImage( "burger.png" ) ?>" alt="rtp icon">
                    <div class="flex d-column  game-features">
                        <p class="bold">RTP </p>
                        <p><?= $meta['other'][0] ?? "" ?></p>
                    </div>

                </div>

            </div>

        </section>
        <section class="casino-filters">
			<?php $header = get_post_meta( get_the_ID(), "title_bf_filters", true );
			?>
            <h2 class="main-header"><?= $header ?> </h2>
            <div class="row mt-4">
                <div class="col-lg-4 col-sm-12">
                    <h6 class="bold">Select bonus</h6>
                    <select id="bonusSelection" class="form-select" aria-label="Default select example">
						<?php
						print  "<option value='' selected>Select</option>";
						foreach ( $bonuses as $bonus ) {
							$fl_title = get_post_meta( $bonus->ID, "filter_title", true ) ?? "";
							print "<option value='$bonus->ID'>$fl_title</option>";
						}
						?>
                    </select>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <h6 class="bold">Select payment</h6>
                    <select id="paymentSelection" class="form-select" aria-label="Default select example">
						<?php
						print  "<option value='' selected>Select</option>";
						foreach ( $payments as $payment ) {
							$fl_title = get_post_meta( $payment->ID, "filter_title", true ) ?? "";
							print "<option value='$payment->ID'>$fl_title</option>";
						}
						?>
                    </select>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <h6 style="color:transparent">a</h6>
                    <button id="filterButton" style="padding: 8px 35px" class="button button-orange">Apply filter
                        <div style='width: 1rem; height: 1rem;' class='spinner-border d-none' role='status'>
                            <span class='visually-hidden'>Loading...</span>
                        </div>
                    </button>

                </div>
            </div>

        </section>
        <div class="pt-4"></div>
		<?php
		$text = get_post_meta( get_the_ID(), "textarea_bf_filters", true ) ?? "";
		print $text;
		?>
		<?php
		$gameId = get_the_ID();
		print do_shortcode( "[casinolist games='$gameId']" ) ?>
        <section class="section-bg-blue">
            <div class="pt-5"></div>
            <h3 style="color: white" class="main-header">
                Why Play <?= get_the_title() ?>
            </h3>
            <div class="mt-2"></div>
            <p style="color:white;">
				<?= $meta['why_play'][0] ?>
            </p>
            <button class="button button-green mt-5"><a rel="nofollow" target="_blank"
                                                        href="<?= $meta['affiliate_link'][0] ?? '' ?>">Play Here</a>
            </button>
            <div class="pb-5"></div>
        </section>
        <div class="page">
            <div class="content-table">
                <div class="content-links" style="text-align: center">
                    <div class="pt-4"></div>
                    <h4 class="main-header" style="display: inline-block">Table of Contents</h4>

                    <button id="insertBefore" class="button button-green m-auto block">
                        <a rel="nofollow" target="_blank"
                           href="<?= $meta['affiliate_link'][0] ?? '' ?>">Play with real money</a></button>
                </div>
            </div>
            <div class="content wp-content">
				<?php the_content(); ?>
            </div>
        </div>
        <section>
			<?php
			print $faqBuilder->BuildFAQ( get_the_ID() );
			?>
        </section>
    </div>

<?php get_footer() ?>