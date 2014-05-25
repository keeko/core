<?php
namespace keeko\core\utils;

trait FilterTrait {
	
	protected function whitelistFilter($array, $whitelist = null) {
		$ret = [];
	
		foreach ($whitelist as $key) {
			if (isset($array[$key])) {
				$ret[$key] = $array[$key];
			}
		}
	
		return $ret;
	}
	
	protected function blacklistFilter($array, $blacklist = []) {
		$ret = [];
	
		foreach (array_keys($array) as $key) {
			if (!in_array($key, $blacklist)) {
				$ret[$key] = $array[$key];
			}
		}
	
		return $ret;
	}
}