<?php
namespace keeko\core\util;


class NameGenerator {

	/**
	 * Transforms a given input into StudlyCase
	 * 
	 * @param string $input
	 * @return string
	 */
	public static function toStudlyCase($input) {
		return ucfirst(preg_replace_callback('/([A-Z-_][a-z]+)/', function($matches) {
			return ucfirst(str_replace(['-','_'], '',$matches[0])); 
		}, $input));
	}
}