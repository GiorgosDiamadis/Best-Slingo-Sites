<?php

class NavWalker extends Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title     = $item->title;
		$permalink = $item->url;
		$output    .= "
        <li class='nav-item'>
        <a href='$permalink' class='footer-a'>
                        $title
                    </a>
                    </li>";

	}
}