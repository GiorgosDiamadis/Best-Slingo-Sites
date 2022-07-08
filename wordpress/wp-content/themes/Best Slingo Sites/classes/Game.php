<?php

class Game {
	private static $game;

	public static function GetConnect(): UI {
		if ( ! isset( self::$game ) ) {
			self::$game = new UI();
		}

		return self::$game;
	}


}