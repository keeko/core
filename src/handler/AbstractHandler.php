<?php
namespace keeko\core\handler;

use keeko\core\handler\ContentHandlerInterface;
use keeko\core\application\Keeko;
use keeko\core\module\ModuleInterface;

abstract class AbstractHandler implements ContentHandlerInterface {

	/**
	 *
	 * @var Keeko
	 */
	protected $keeko;

	public function setKeeko(Keeko $application) {
		$this->keeko = $application;
	}

	/**
	 *
	 * @param array $match        	
	 * @return ModuleInterface
	 */
	protected function getModule($match) {
		$mm = $this->keeko->getModuleManager();
		return $mm->load($match['module']);
	}
}