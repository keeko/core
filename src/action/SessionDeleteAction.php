<?php
namespace keeko\core\action;

use keeko\core\action\base\SessionDeleteActionTrait;
use keeko\framework\foundation\AbstractAction;

/**
 * Deletes a session
 * 
 * @author gossi
 */
class SessionDeleteAction extends AbstractAction {

	use SessionDeleteActionTrait;
}
