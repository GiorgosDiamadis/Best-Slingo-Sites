<?php
require_once __DIR__ . "/../classes/Injectable.php";

class Container {
	private static array $container;

	public static function Add( string $name, Injectable $class ): void {
		self::$container[ $name ] = $class;
	}


	public static function Get( string $name ): Injectable {
		return self::$container[ $name ];
	}
}