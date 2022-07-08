<?php

class UI implements Injectable {


	public function Archive( $data ): string {

		if ( is_a( $data, 'WP_POST' ) ) {
			$id        = $data->ID ?? 0;
			$title     = $data->post_title ?? "";
			$excerpt   = $data->post_excerpt ?? "";
			$link      = get_permalink( $id ) ?? "";
			$pipes     = Container::Get( 'helper' )->GetThumbnail( $id, 'archive-thumbnail' );
			$thumbnail = is_array( $pipes ) ? $pipes[0] : "";
		} else {
			$id        = $data['ID'] ?? 0;
			$title     = $data['post_title'] ?? "";
			$excerpt   = $data['post_excerpt'] ?? "";
			$link      = get_permalink( $id ) ?? "";
			$pipes     = Container::Get( 'helper' )->GetThumbnail( $id, 'archive-thumbnail' );
			$thumbnail = is_array( $pipes ) ? $pipes[0] : "";
		}
		$alt = $title . " thumbnail ";


		$excerpt = do_shortcode( $excerpt );
		$res     = "<article>
          <a aria-label='$alt' href='$link'><img src='$thumbnail' width='336' height='198' alt='$alt'></a>
          <div class='article-info hide-1000'>
              <h3>$title</h3>
              <p class='excerpt mt-2'>$excerpt
              </p>
              <button class='button button-orange' style='padding: 15px 20px'><a
                          href='$link'>Read More</a>
              </button>
          </div>
          <h4 style='display: none' class='show-1000'>$title</h4>
          <button class='button button-orange show-1000' style='display: none;padding: 15px 20px'><a
                      href='$link'>Read More</a>
          </button>
      </article>
";

		return $res;
	}

	public function CasinoListItem( $data ): string {
		$hp        = Container::Get( 'helper' );
		$thumb     = $hp->GetThumbnail( $data->ID, 'casino-list-item' );
		$checkmark = wp_get_attachment_image_src( 85, 'thumbnail' );
		$code      = $hp->GetImage( 'copy.png' );

		$slingoGames  = count( get_post_meta( $data->ID, 'games' ) );
		$rated        = get_post_meta( $data->ID, 'top_rated', true );
		$codeCopy     = get_post_meta( $data->ID, 'code', true );
		$affiliate    = get_post_meta( $data->ID, 'affiliate_link', true ) ?? "";
		$welcomeBonus = get_post_meta( $data->ID, 'welcome_bonus', true );
		$freeSpins    = get_post_meta( $data->ID, 'fr_spins', true );
		$expiry       = get_post_meta( $data->ID, 'expiry_period', true );
		$wagering     = get_post_meta( $data->ID, 'wagering_t', true );
		$aboutBonus   = get_post_meta( $data->ID, "about_cb", true );
		$pros         = get_post_meta( $data->ID, "pros", true );
		$terms        = get_post_meta( $data->ID, "terms", true );


		$wageringName = get_term( $wagering )->name ?? " ";
		$link         = get_permalink( $data->ID );

		$hp       = Container::Get( 'helper' );
		$prosHtml = $hp->ProsCons( $pros, "pros", 3 );

		$arrowImg = "https://bestslingosites.co.uk/wp-content/uploads/2022/06/arrowdown.png";

		$alt = $data->post_title . " thubmanail ";

		$codeHtml = $codeCopy != null ? "<div class='code'>$codeCopy <img src='$code' width='20' height='18' alt=''></div>" : "<div class='code' style='width: unset!important;display: block'><strong>No code required</strong></div>";

		if ( $rated > 3 ) {
			$topRated = "";
		} else {
			$img      = "https://bestslingosites.co.uk/wp-content/uploads/2022/06/toprated" . $rated . ".png";
			$topRated = "<img src='$img' class='rated' width='70'
                         height='70' alt='top-rated-1'>";
		}

		return "
		<div class='col-lg-12 col-md-6 casino-list-item flex align-center justify-center d-column'>
            <div class='flex align-center d-column general'>
                <div class='info flex align-center justify-center'>
		                $topRated
		                <a href='$link' aria-label='$alt' style='margin-top: 0'>
		                    <img style='border-radius:8px;width: 200px!important;' src='$thumb[0]' width='200' height='111' alt='$alt'>
		                </a>
		                    <div class='text-list'>
		                        <h5>Why Play</h5>
		                        $prosHtml
		                    </div>
		                    <div class='slingo-games'>Slingo Games $slingoGames</div>
		                    $codeHtml
		
		                    <div class='cta'>
		                        <button class='button button-green'> <a rel='nofollow' target='_blank' href='$affiliate'>Claim Bonus</a></button>
		                        <div style='text-align: center;margin-top: 1rem;'><a href='$link'>Read review</a></div>
		                    </div>
		        </div>
		
		            <div class='terms'>
		                $terms
		            </div>
		
		
		            <p href='#' class='more-details'>
	                    More Details <img src='$arrowImg' width='14' height='9'
	                                      alt=''>
	                </p>	
            	</div>
            <div class='details justify-center align-center'>
                <div class='left'>
                    <p><span>Bonus</span> <span>$welcomeBonus</span></p>
                    <p><span>Extra spins</span> <span>$freeSpins</span></p>
                    <p><span>Wagering</span> <span>$wageringName</span></p>
                    <p><span>Expiry Period</span> <span>$expiry</span></p>
                </div>
                <div class='right'>
                    <h5>About casino bonus</h5>
                    $aboutBonus
                    <a href='$link' style='text-align: left'>Read full review</a>
                </div>
            </div>
        </div>";
	}

	public function CasinoCard( $data, $isSingle = false ): string {
		$title      = $data->post_title;
		$imgId      = get_post_meta( $data->ID, "img100", true );
		$thumb      = wp_get_attachment_image_src( $imgId, 'full' );
		$thumbImage = is_array( $thumb ) ? $thumb[0] : "";
		$desktop    = $isSingle ? "desktop" : "";

		$no_deposit_bonus = get_post_meta( $data->ID, "no_deposit_bonus", true ) ?? "No code required";
		$sh_title         = get_post_meta( $data->ID, "sh_title", true );
		$affLink          = get_post_meta( $data->ID, "affiliate_link", true ) ?? "";
		$link             = get_permalink( $data->ID );
		$rated            = get_post_meta( $data->ID, 'top_rated', true );

		$hp = Container::Get( 'helper' );

		$overall = $hp->CalculateRating( $data->ID );

		$stars = self::Stars( $overall );

		$alt = $title . " thumbnail ";

		$img = 'https://bestslingosites.co.uk/wp-content/uploads/2022/06/toprated' . $rated . '.png';

		ob_start();

		?>
        <div class='col-xl-4  col-md-6'>
            <div class='casino-card  pb-4 pt-4 mt-3 <?= $desktop ?>'>
                <div class="flex d-column justify-center right <?= $desktop ?> align-center">
					<?php if ( $sh_title != null && $sh_title != '' ): ?>
                        <div class='header <?= $desktop ?>'>
                            <!--							--><?php //if ( $rated < 3 ): ?>
                            <!--                                <img src='-->
							<? //= $img ?><!--' class='rated' width='50'-->
                            <!--                                     height='50' alt='top-rated-1'-->
                            <!--                                     style="height: 40px!important;width: 40px!important;">-->
                            <!--							--><? // endif; ?>
                            <h5><?= $sh_title ?></h5>
                        </div>
					<?php endif ?>
                    <a aria-label="$alt" href='<?= $link ?>'>
                        <img src='<?= $thumbImage ?>' width='150' height='150' alt='<?= $alt ?>'>
                    </a>
                    <h5 style='color:white;'><?= $title ?></h5>
                </div>
                <div class="flex d-column justify-center align-center left <?= $desktop ?>">
                    <p class='bonus <?= $desktop ?>'> <?= $no_deposit_bonus ?></p>
                    <div class='rating <?= $desktop ?>'>
                        <div class='rating-info <?= $desktop ?>'>
                            <h5 class='mb-0'
                                style='text-align: center;font-weight: bold;color: white'><?= $overall ?></h5>
							<?= $stars ?>
                            <a href='<?= $link ?>'>
                                <p style='color: white;text-decoration: underline;text-align: center'>Read review</p>
                            </a>
                        </div>
                        <p class="bonus <?= $desktop ?>"><?= $no_deposit_bonus ?></p>
                        <a rel='nofollow' target='_blank' href='<?= $affLink ?>'>
                            <button class='button button-orange' style='padding: 6px 27px;vertical-align: text-bottom'>
                                Visit
                                Casino
                            </button>
                        </a>

                    </div>
                </div>

            </div>
        </div>
		<?php

		return ob_get_clean();

	}

	public function Stars( $n ): string {
		$hp        = Container::Get( 'helper' );
		$res       = "<div class='stars'>";
		$starFull  = "https://bestslingosites.co.uk/wp-content/uploads/2022/06/starfilled.png";
		$starEmpty = "https://bestslingosites.co.uk/wp-content/uploads/2022/06/starempty.png";


		for ( $i = 0; $i < $n; $i ++ ) {
			$res .= "<span><img class='class-rating' src='$starFull' width='20'
                                       height='20' alt='full star'></span>";
		}

		for ( ; $i < 10; $i ++ ) {
			$res .= "<span><img class='class-rating' src='$starEmpty' width='20'
                                       height='20' alt='half star'></span>";
		}

		$res .= "</div>";

		return $res;
	}

	public function CasinoTags( $data ): string {
		$shTitle = get_post_meta( $data->ID, "sh_title", true );
		$title   = $data->post_title;
		$link    = get_permalink( $data->ID );

		return "<p><span>$shTitle</span> <span><a  style='text-decoration: none' href='$link'>$title</a></span></p>";
	}


	public function GameUI( $data, $hasButton = true, $linkImage = true ): string {
		if ( is_a( $data, 'WP_POST' ) ) {
			$post_title = $data->post_title;
			$id         = $data->ID;
		} else {
			$post_title = $data['post_title'];
			$id         = $data['ID'];
		}

		$link   = $linkImage ? get_permalink( $id ) : "";
		$alt    = $post_title . " thumbnail ";
		$thumb  = Container::Get( 'helper' )->GetThumbnail( $id, 'card-thumbnail' );
		$a      = $linkImage
			? "<a aria-label='$alt' href='$link'><img  style='max-width:100%' src='$thumb[0]' width='373' height='210'  alt='$alt'></a>"
			: "<a href='#' onclick='return false' aria-label='$alt'><img  style='max-width:100%' src='$thumb[0]' width='373' height='210'  alt='$alt'></a>";
		$button = $hasButton ? "<a style='text-decoration:none' href='$link'><button style='padding: 1px 20px' class='button button-gray block m-auto mb-3'>Play Free
                </button></a>" : "";

		return "
        <div class='col-xl-4 col-lg-6 col-12 ' style='position:relative;'> 
            <div  style='max-width:100%' data-game-id='$id' class='card-simple flex d-column justify-center align-center'>
            	$a
                <h4 class='slot-title m-auto'>$post_title</h4>
                $button
            </div>
        </div>";
	}

	public function ArticleUI( $data, $showCategory = true, $linkImage = true ): string {
		$id         = $data->ID;
		$post_title = $data->post_title;
		$thumb      = Container::Get( 'helper' )->GetThumbnail( $id, 'card-thumbnail' );
		$link       = get_permalink( $id );
		$alt        = $post_title . " thumbnail ";


		if ( $showCategory ) {
			$category     = get_the_category( $id );
			$categoryName = "";
			$categorySlug = "";
			if ( $category ) {
				$categoryName = $category[0]->name;
				$categorySlug = $category[0]->slug;
			}
		}
		ob_start();
		?>

        <div class='col-xl-4 col-lg-6 col-12 '>
            <div style='max-width:100%' data-article-id='<?= $id ?>'
                 class='card-simple flex d-column justify-center align-center pb-2'>
				<?php if ( $showCategory ): ?>

                    <button class='button button-gray category'>
                        <a aria-label='category' style="margin-top: 0"
                           href="<?= get_home_url() . '/category/' . $categorySlug ?>"><?= $categoryName ?>
                        </a>
                    </button>

				<? endif; ?>

				<?php if ( $linkImage ): ?>
                    <a aria-label="<?= $alt ?>" href='<?= $link ?>' style='margin-top: 0'><img style='max-width:100%;'
                                                                                               src='<?= $thumb[0] ?>'
                                                                                               width='373' height='210'
                                                                                               alt='<?= $alt ?>'></a>
				<?php else: ?>
                    <a href='#' aria-label="<?= $alt ?>" onclick='return false' style='margin-top: 0'><img
                                style='max-width:100%;'
                                src='<?= $thumb[0] ?>'
                                width='373' height='210'
                                alt='<?= $alt ?>'></a>
				<? endif; ?>
                <h4 class='slot-title m-auto'><?= $post_title ?></h4>
            </div>
        </div>
		<?php

		return ob_get_clean();


	}
}
