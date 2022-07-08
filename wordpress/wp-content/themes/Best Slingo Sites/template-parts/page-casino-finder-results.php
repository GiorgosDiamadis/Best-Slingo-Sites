<?php
get_header();
?>

<div class="container">
    <header class="flex justify-center d-column">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a style="color: white" href="<?= get_home_url() ?>">Home</a></li>
                <li class="breadcrumb-item active-bread" style="color: white"
                    aria-current="page"><?= get_the_title() ?></li>
            </ol>
        </nav>
        <h1 class="main-header" style="color: white;"><?= get_the_title() ?></h1>
    </header>

    <div class="page">
        <div class="content wp-content">
			<?php the_content(); ?>
        </div>
    </div>
</div>
<?php get_footer() ?>
