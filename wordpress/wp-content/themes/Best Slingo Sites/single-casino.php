<?php
get_header();
$db                     = Container::Get( 'db' );
$ui                     = Container::Get( 'ui' );
$hp                     = Container::Get( 'helper' );
$adDisclosure           = $db->GetAdDisclosure();
$meta                   = get_post_meta( get_the_ID() );
$ratings                = [];
$ratings['reliability'] = $meta['readability'][0];
$ratings['banking']     = $meta['banking'][0];
$ratings['customer']    = $meta['customer_service'][0];
$ratings['bonuses']     = $meta['bonuses'][0];
$ratings['games']       = $meta['games_rating'][0];
$ratings['mobile']      = $meta['mobile_experience'][0];
$faqBuilder             = Container::Get( 'faqBuilder' );
$sizes                  = $hp->GetImageUrls( get_the_ID() );
$alt                    = get_the_title() . " thumbnail ";
?>

<div class="container">
    <header class="justify-center align-center">
        <nav class="show-1000" style="display: none;--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a style="color: white" href="<?= get_home_url() ?>">Home</a></li>
                <li class="breadcrumb-item"><a style="color: white"
                                               href="<?= get_home_url() . "/slingo-reviews" ?>">Slingo
                        Reviews</a>
                </li>
                <li class="breadcrumb-item active-bread" style="color: white"
                    aria-current="page"><?= get_the_title() ?></li>
            </ol>
        </nav>
        <div class="casino-single">
            <div data-bs-toggle="tooltip" data-bs-placement="bottom"
                 title="<?= $adDisclosure ?>"
                 class="flex align-center tooltip-casino-single hide-1000">
                <img src="https://bestslingosites.co.uk/wp-content/uploads/2022/06/info_24px.png" width="20" height="20" alt="info icon">
                <p style="margin: 0;font-size: .8rem;width: 72px">Advertising Disclosure</p>
            </div>
            <nav class="hide-1000" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= get_home_url() ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= get_home_url() . "/slingo-reviews" ?>">Slingo Reviews</a>
                    </li>
                    <li class="breadcrumb-item active-bread"
                        aria-current="page"><?= get_the_title() ?></li>
                </ol>
            </nav>

            <h1 style="margin: 0"><?= get_the_title() ?></h1>
			<?= do_shortcode( '[score]' ) ?>
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
                                                                    href="<?= $meta['affiliate_link'][0] ?? '' ?>">Visit
                                Casino</a>
                        </button>

                    </div>
                </div>

            </div>


        </div>
    </header>
    <section>
        <div class="pt-4"></div>
        <h3 class="main-header" style="margin: 0">Bonus Details</h3>
        <div class="mt-2 grid grid-2 bonus-details">
            <div class="mt-2 mb-2">
                <p class="bold">Welcome Bonus</p>
                <p><?= $meta['welcome_bonus'][0] ?? "" ?></p>
            </div>
            <div class="mt-2 mb-2">
                <p class="bold">Expiry Period</p>
                <p><?= $meta['expiry_period'][0] ?? "" ?></p>
            </div>
            <div class="mt-2 mb-2">
                <p class="bold">No Deposit Bonus</p>
                <p><?= $meta['no_deposit_bonus'][0] ?? "" ?></p>
            </div>
            <div class="mt-2 mb-2">
                <p class="bold">Max Winnings</p>
                <p><?= $meta['max_winning'][0] ?? "" ?></p>
            </div>
            <div class="mt-2 mb-2">
                <p class="bold">Wagering</p>
                <p><?= get_term( $meta['wagering_t'][0] )->name ?? "" ?></p>
            </div>
            <div class="mt-2 mb-2">
                <p class="bold">Max Bet</p>
                <p><?= $meta['max_bet'][0] ?? "" ?></p>
            </div>
        </div>
        <p class="mt-3" style="text-align: justify"><?= $meta['terms'][0] ?></p>
        <div class="pt-4"></div>
    </section>
    <section class="section-bg-blue">
        <div class="pt-4"></div>
        <h3 style="color: white" class="main-header">
            Pros and Cons
        </h3>


        <div class="mt-4 mt-2 grid grid-2 bonus-details">


            <div style="color: white">
				<?= $hp->ProsCons( $meta['pros'][0], "mt-2 mb-2 pros" ) ?>
            </div>
            <hr style="display: none" class="show-600">
            <div style="color: white">
				<?= $hp->ProsCons( $meta['cons'][0], "mt-2 mb-2 cons", ) ?>
            </div>
            <div class="pt-4"></div>
        </div>
    </section>
    <div class="page">
        <div class="content-table">
            <div class="content-links">
                <h4 class="main-header">Table of Contents</h4>
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
