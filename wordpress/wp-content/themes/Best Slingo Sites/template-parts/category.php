<?php
get_header();
$categoryName = get_the_category()[0]->name;
$cfg          = Container::Get( 'config' );
$args         = array(
	'post_type'      => 'post',
	'posts_per_page' => $cfg->GetPostPagination(),
	'category_name'  => $categoryName,
	'post_status'    => 'publish',
);

$archives = new WP_Query( $args );
$ui       = Container::Get( 'ui' );

?>
<div class="container">
    <header class="flex justify-center d-column">
        <h1 class="main-header" style="color: white;"><?= $categoryName ?></h1>
    </header>
    <div class="articles" data-page='1' data-filter-page="1"
         active-filters='{"category":"<?= $categoryName ?>","type":"post"}'>
		<?php
		foreach ( $archives->posts as $archive ) {
			print $ui->Archive( $archive );
		}
		?>
    </div>
    <button onclick="loadMore(this,'post','.articles')" class="button button-gray block m-auto mb-4">Load more articles
        <div style="width: 1rem; height: 1rem;" class="spinner-border d-none" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </button>
</div>
<?php
get_footer();
?>
