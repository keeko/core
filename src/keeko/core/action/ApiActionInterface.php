<?php
namespace keeko\core\action;

use keeko\core\action\ActionInterface;

interface ApiActionInterface extends ActionInterface {

	public function toJson();
}