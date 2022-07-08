<?php
/*
 * Plugin Name: Casino Reordering
 * Description: Edit ad disclosure text
 * Version: 1.0
 * Author: Diamadis Giorgos
 */

class CasinoReordering {

	public function __construct() {
		add_action( "admin_menu", array( $this, "adminPage" ) );
	}


	function adminPage() {
		add_menu_page( "Reorder Casinos",
			"Reorder Casinos",
			"manage_options",
			__FILE__, array( $this, "HTML" ) );
	}

	function HTML() {

		$query  = new WP_Query(
			array(
				'post_type'      => 'casino',
				'post_status'    => 'publish',
				'meta_key'       => 'top_rated',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
				'posts_per_page' => - 1
			)
		);
		$output = '<ul class="draggable-list" id="draggable-list">';

		foreach ( $query->posts as $post ) {
			$link  = get_permalink( $post->ID );
			$order = rwmb_meta( "top_rated", null, $post->ID );

			$output .= "<li  data-index='{$order}'>
                    <span class='number'>{$order}</span>
                    <div id='{$order}' class='draggable' draggable='true'>
                        <input type='hidden' value='{$post->ID}'>
                        <a href='{$link}'>{$post->post_title}</a>
                    </div>
                </li>";

		}

		$output .= "</ul><button  class='load-more' onclick='reOrder()'>Reorder!</button> ";

		echo $output;
	}
}


$adDiscolure = new CasinoReordering();
