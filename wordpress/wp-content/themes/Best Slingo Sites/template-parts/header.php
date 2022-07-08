<!DOCTYPE html>
<html lang="en">
<?php
$helper = Container::Get( 'helper' );
?>
<head>
    <meta charset="UTF-8">
    <title><?= RankMath\Post::get_meta( 'title' ) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= RankMath\Post::get_meta( 'description' ) ?>">
    <meta name="robots" content="index,follow">
<!--    <link rel="preconnect" href="https://fonts.googleapis.com">-->
<!--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
<!--    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="<?=get_template_directory_uri() . '/bootstrap.min.css'?>">

    <link rel="stylesheet"
          href="<?= get_template_directory_uri() . '/style.css?ver=' . filemtime( dirname( __FILE__ ) . '/style.css' ) ?> ">
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-210004563-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-210004563-1');
    </script>

</head>

<body>
<nav class="navbar sticky-top navbar-expand-lg bg-dark navbar-dark">
    <div class="container-xxl">
        <a class="navbar-brand" id="navLogo" href="<?= get_home_url() ?>">
            <img width="190" src="<?= $helper->GetImage( 'logo.png' ) ?>" alt="">

        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" style="justify-content: end" id="navbarNav">
			<?php
			require_once __DIR__ . "/classes/MenuWalker.php";
			wp_nav_menu(
				array(
					'theme_location' => 'header',
					'menu_class'     => 'navbar-nav main-nav',
					'walker'         => new NavWalker(),
				)
			)
			?>
        </div>
    </div>
</nav>