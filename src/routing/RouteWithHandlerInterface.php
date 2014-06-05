<?php
namespace keeko\core\routing;

use keeko\core\handler\ContentHandlerInterface;

interface RouteWithHandlerInterface {

	/**
	 *
	 * @return ContentHandlerInterface
	 */
	public function getHandler();
}