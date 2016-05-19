<?php
namespace keeko\core\event;

use keeko\core\model\Action;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class ActionEvent extends Event {

	/**
	 */
	const POST_APIS_ADD = 'core.action.post_apis_add';

	/**
	 */
	const POST_APIS_REMOVE = 'core.action.post_apis_add';

	/**
	 */
	const POST_APIS_UPDATE = 'core.action.post_apis_update';

	/**
	 */
	const POST_CREATE = 'core.action.post_create';

	/**
	 */
	const POST_DELETE = 'core.action.post_delete';

	/**
	 */
	const POST_GROUPS_ADD = 'core.action.post_groups_add';

	/**
	 */
	const POST_GROUPS_REMOVE = 'core.action.post_groups_add';

	/**
	 */
	const POST_GROUPS_UPDATE = 'core.action.post_groups_update';

	/**
	 */
	const POST_MODULE_UPDATE = 'core.action.post_module_update';

	/**
	 */
	const POST_SAVE = 'core.action.post_save';

	/**
	 */
	const POST_UPDATE = 'core.action.post_update';

	/**
	 */
	const PRE_APIS_ADD = 'core.action.pre_apis_add';

	/**
	 */
	const PRE_APIS_REMOVE = 'core.action.pre_apis_add';

	/**
	 */
	const PRE_APIS_UPDATE = 'core.action.pre_apis_update';

	/**
	 */
	const PRE_CREATE = 'core.action.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.action.pre_delete';

	/**
	 */
	const PRE_GROUPS_ADD = 'core.action.pre_groups_add';

	/**
	 */
	const PRE_GROUPS_REMOVE = 'core.action.pre_groups_add';

	/**
	 */
	const PRE_GROUPS_UPDATE = 'core.action.pre_groups_update';

	/**
	 */
	const PRE_MODULE_UPDATE = 'core.action.pre_module_update';

	/**
	 */
	const PRE_SAVE = 'core.action.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.action.pre_update';

	/**
	 */
	protected $action;

	/**
	 * @param Action $action
	 */
	public function __construct(Action $action) {
		$this->action = $action;
	}

	/**
	 * @return Action
	 */
	public function getAction() {
		return $this->action;
	}
}
