<?php
namespace keeko\core\handler;

use keeko\core\handler\ContentHandlerInterface;
use keeko\core\application\Keeko;

abstract class AbstractHandler implements ContentHandlerInterface {

	/**
	 * @var Keeko
	 */
	protected $keeko;
	
	public function setKeeko(Keeko $application){ 
		 $this->keeko = $application;
	}

}