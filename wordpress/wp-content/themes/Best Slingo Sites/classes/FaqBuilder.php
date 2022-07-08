<?php

class FaqBuilder implements Injectable {

	public function BuildFAQ( $id ): string {
		global $wpdb;
		$hp         = Container::Get( 'helper' );
		$table_name = $wpdb->prefix . 'faq';
		$id         = get_the_ID();
		$res        = "";
		$faqs       = $wpdb->get_results( "select * from $table_name
                                                        where post_id=$id" );
		$arrow      = "https://bestslingosites.co.uk/wp-content/uploads/2022/07/Vector.png";

		$text = count( $faqs ) > 0 ? "<h3 class='main-header'>FAQ</h3>" : "";
		$res  .= " <div class='pt-4'></div>
        $text
        <div class='pt-4'></div>
          <div class='casino-list'>";
		for ( $i = 0; $i < count( $faqs ); $i ++ ) {
			$question = $faqs[ $i ]->question;
			$answer   = $faqs[ $i ]->answer;
			$res      .= "

<div class='casino-list-item flex align-center justify-center d-column'>
                                                            <div class='flex  general' style='width: 100%;justify-content: space-between;
                                                                align-items: center;padding: 0 1rem'>
                                                                <h6 style='font-size:1.2rem;font-weight: bold;margin-bottom: 0'>$question</h6>
                                            
                                            
                                                                <p href='#' class='more-details'>
                                                                    <img class='arrow-faq' src='$arrow' width='33'
                                                                         height='34'
                                                                         alt=''>
                                                                </p>
                                                            </div>
                                            
                                                            <div class='details faq justify-center align-center'>
                                                            $answer
                                                            </div>
                                                        </div>";
		}
		$res .= "</div>";

		return $res;
	}
}