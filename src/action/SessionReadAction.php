<?php
namespace keeko\core\action;

use keeko\core\action\base\SessionReadActionTrait;
use keeko\framework\foundation\AbstractAction;

/**
 * Reads a session
 * 
 * @author gossi
 */
class SessionReadAction extends AbstractAction {

	use SessionReadActionTrait;
}
