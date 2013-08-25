<?php
namespace keeko\core\handler;

use keeko\core\application\ApplicationContentInterface;
use keeko\core\application\Keeko;

interface ContentHandlerInterface {
	
	/**
	 * Sets the Keeko application
	 * 
	 * @param Keeko $application
	 */
	public function setKeeko(Keeko $application);

	/**
	 * Returns an associative array with keys for layout blocks
	 * and ApplicationContent as value.
	 * 
	 * @param $match the result from a router match
	 * @return ApplicationContentInterface[]
	 */
	public function getContents($match);
	
// 	/**
// 	 * Returns the main menu for that content
// 	 * 
// 	 * @param ApplicationContentInterface $match
// 	 */
// 	public function getMainMenu(ApplicationContentInterface $match);
	
	
	/**
	 * Returns an identifier to a specific layout that should be used
	 * or <code>null</code> if nothing special should be used.
	 * 
	 * @param $match the result from a router match
	 * @return String layout identifier
	 */
	public function getLayout($match);
}