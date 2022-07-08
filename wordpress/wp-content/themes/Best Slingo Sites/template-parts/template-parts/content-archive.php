<?php
$hp = Container::Get( 'helper' );
?>

<article>
    <img src="<?= $hp->GetThumbnail( get_the_ID() )[0] ?>" width="440" height="240" alt="">
    <div class="article-info hide-1000">
        <h3><?= get_the_title() ?></h3>
        <p class="excerpt"><?= get_the_excerpt() ?>
        </p>
        <button class="button button-orange" style="padding: 15px 20px"><a
                    href="<?= get_permalink( get_the_ID() ) ?>">Read More</a>
        </button>
    </div>
    <h4 style="display: none" class="show-1000"><?= get_the_title() ?></h4>
    <button class="button button-orange show-1000" style="display: none;padding: 15px 20px"><a
                href="<?= get_permalink( get_the_ID() ) ?>">Read More</a>
    </button>
</article>
