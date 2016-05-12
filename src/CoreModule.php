<?php
namespace keeko\core;

use keeko\framework\foundation\AbstractModule;

/**
 * @license MIT
 * @author gossi
 */
class CoreModule extends AbstractModule {
	
	const EXT_LISTENER = 'keeko.core.listener';
	
	protected function initialize() {
		
	}

	/**
	 */
	public function install() {
	}

	/**
	 */
	public function uninstall() {
	}

	/**
	 * @param mixed $from
	 * @param mixed $to
	 */
	public function update($from, $to) {
	}
}
