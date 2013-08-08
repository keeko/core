<?php
namespace keeko\core\handler;

use keeko\core\application\ApplicationContentInterface;

interface ContentHandlerInterface {

	/**
	 * @param $match the result from a router match
	 * @return ApplicationContentInterface
	 */
	public function getMainContent($match);

	/**
	 * Returns an associative array with keys for layout blocks
	 * and ApplicationContent as value.
	 * 
	 * @param $match the result from a router match
	 * @return ApplicationContentInterface[]
	 */
	public function getAdditionalContent($match);
}