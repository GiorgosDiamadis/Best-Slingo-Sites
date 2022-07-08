<?php
$hp          = Container::Get( 'helper' );
$scrolltop   = $hp->GetImage( 'scrolltop.png' );
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$re          = '/\/slingo-reviews\/(?=.*[a-zA-Z])([a-zA-Z0-9_-]+\/?)$/m';

preg_match_all( $re, $actual_link, $matches, PREG_SET_ORDER, 0 );
?>
<div class="cookie-notice">
    <p>
        By continue to use our site you agree to us using cookies in accordance with our <a href="/cookies-policy">Cookie
            Policy</a>.
    </p>
    <h6 onclick="cookiePolicy()"
        style="margin: auto;display: block;text-align: center;font-weight: bold;text-decoration: underline"
        class="mt-2 mb-2">GOT IT</h6>
</div>
<?php
$re = '/\/use-slingo-finder\//m';
preg_match_all( $re, $actual_link, $match, PREG_SET_ORDER, 0 );
if ( ! $match ) {
	?>
    <div class="sticky-header reveal-after-cf">
		<?php if ( $matches ): ?>

            <p>
				<?= get_post_meta( get_the_ID(), "welcome_bonus", true ) ?? "" ?>

            </p>
            <button class="button button-green" style="border-radius: 100px;"><a rel='nofollow' target='_blank'
                                                                                 href="<?= get_post_meta( get_the_ID(), "affiliate_link", true ) ?? '' ?>">Visit
                    Casino</a></button>
		<?php else: ?>
            <button class="button button-orange "
                    style="border-radius: 100px;padding: 10px 20px ;min-width: 200px"><a
                        href="<?= get_home_url() ?>/use-slingo-finder">Use Slingo Site Finder</a></button>


		<?php endif ?>


    </div>
	<?php
}
?>
<div class="footer">
    <div class="container-xxl">
        <div class="footer-info">
            <div class="footer-info-div">
				<?php
				$db = Container::Get( 'db' );
				?>
                <h5 style="color:white; font-weight:bold">About Our Site</h5>
                <p class="mt-4" style="color:rgba(242, 242, 242, 0.8)">
					<?= $db->GetFooterText() ?>
                </p>
            </div>
			<?php
			require_once __DIR__ . "/classes/MenuWalker.php";
			$helper = Container::Get( 'helper' );

			?>
            <div class="footer-info-div">
                <h5 style="color:white; font-weight:bold">New Slingo Sites</h5>
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'footer-menu1',
						'menu_class'      => 'navbar-nav',
						'walker'          => new NavWalker(),
						'container_class' => 'footer-ul',
					)
				);
				?>
            </div>
            <div class="footer-info-div">
                <h5 style="color:white; font-weight:bold">New Slingo Games</h5>
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'footer-menu2',
						'menu_class'      => 'navbar-nav',
						'walker'          => new NavWalker(),
						'container_class' => 'footer-ul',
					)
				);
				?>
            </div>
            <div class="footer-info-div">
                <h5 style="color:white; font-weight:bold">More</h5>
				<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'footer-menu3',
						'menu_class'      => 'navbar-nav',
						'walker'          => new NavWalker(),
						'container_class' => 'footer-ul',
					)
				);
				?>
            </div>
			<?php
			?>
        </div>
        <a style="display:inline;" class="footer-img" href="https://www.begambleaware.org/">
            <img width="212" height="30" src="<?= $helper->GetImage( 'begambleaware.webp' ) ?>" alt=""
                 style="background-color: aliceblue;border-radius: 10px;" class="mt-4 mb-2">
        </a>
        <a style="display:inline;" class="footer-img" href="https://www.gamcare.org.uk/">
            <img width="111" height="40" src="<?= $helper->GetImage( 'gamcare.webp' ) ?>" alt=""
                 style="background-color: aliceblue;border-radius: 10px;margin-left: 2rem" class="mt-4 mb-2">

        </a>
        <a style="display:inline;" class="footer-img" href="https://www.gamstop.co.uk/ ">
            <img width="153" height="30" src="<?= $helper->GetImage( 'gamstop.webp' ) ?>" alt=""
                 style="margin-left: 2rem" class="mt-4 mb-2">

        </a>
        <a style="display:inline;" href="" class="footer-img">
            <img width="40" height="40" src="<?= $helper->GetImage( '18+.webp' ) ?>" alt="" style="margin-left: 2rem"
                 class="mt-4 mb-2">
        </a>


        <p class="mt-2 " style="color: white;font-weight: bold">
            All rights reserved. Copyright 2022.
        </p>


    </div>
</div>
<img width="48" height="48" onclick="window.scrollTo(0,0)" src="<?= $scrolltop ?>" id="scrolltop" alt="Scroll top icon">


<script src="<?= get_template_directory_uri() . '/jquery.min.js'?>"></script>
<script src="<?= get_template_directory_uri() . '/bootstrap.bundle.min.js'?>"></script>
</script>
<script src="<?= get_template_directory_uri() . '/main.js?ver=' . filemtime( dirname( __FILE__ ) . '/main.js' ) ?>"></script>
<script src="<?= get_template_directory_uri() . "/api.js?ver=" . filemtime( dirname( __FILE__ ) . '/api.js' ) ?>"></script>
<script src="<?= get_template_directory_uri() . "/UiBuilder.js?ver=" . filemtime( dirname( __FILE__ ) . '/UiBuilder.js' ) ?>"></script>
<script>
    const casinoFinder = new CasinoFinder();
</script>

</body>

</html>