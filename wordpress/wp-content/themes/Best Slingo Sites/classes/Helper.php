<?php

class Helper implements Injectable {

	public function GetImage( $name ): string {
		return get_template_directory_uri() . "/images/$name";
	}

	public function ProsCons( string $sentence, string $class = "", int $num = - 1 ): string {
		$sentences = explode( ";", $sentence );

		$prosHtml = "";
		$show     = $num > - 1 ? $num : count( $sentences );

		for ( $i = 0; $i < count( $sentences ) && $i < $show; $i ++ ) {
			if ( $sentences[ $i ] != "" ) {
				$prosHtml .= " <p class='text-list-item $class'>
                        $sentences[$i]
                            
                        </p>";
			}
		}

		return $prosHtml;
	}

	public function GetImageUrls( $imgId ): array {
		$sizes = get_intermediate_image_sizes();
		$urls  = [];

		foreach ( $sizes as $size ) {
			$urls[ $size ] = $this->GetThumbnail( $imgId, $size )[0];
		}

		return $urls;
	}

	public function GetThumbnail( $id, $size = 'thumbnail' ) {
		return wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );
	}

	public function CalculateRating( $id ): float {
		$meta                   = get_post_meta( $id );
		$ratings                = [];
		$ratings['reliability'] = $meta['readability'][0];
		$ratings['banking']     = $meta['banking'][0];
		$ratings['customer']    = $meta['customer_service'][0];
		$ratings['bonuses']     = $meta['bonuses'][0];
		$ratings['games']       = $meta['games_rating'][0];
		$ratings['mobile']      = $meta['mobile_experience'][0];

		return round( $ratings['reliability'] * 0.3 +
		              $ratings['banking'] * 0.25 +
		              $ratings['customer'] * 0.15 +
		              $ratings['bonuses'] * 0.15 +
		              $ratings['games'] * 0.1 +
		              $ratings['mobile'] * 0.05, 2 );
	}
}