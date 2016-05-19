<?php
namespace keeko\core\event;

use keeko\core\model\Module;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class ModuleEvent extends Event {

	/**
	 */
	const POST_ACTIONS_ADD = 'core.module.post_actions_add';

	/**
	 */
	const POST_ACTIONS_REMOVE = 'core.module.post_actions_add';

	/**
	 */
	const POST_ACTIONS_UPDATE = 'core.module.post_actions_update';

	/**
	 */
	const POST_CREATE = 'core.module.post_create';

	/**
	 */
	const POST_DELETE = 'core.module.post_delete';

	/**
	 */
	const POST_SAVE = 'core.module.post_save';

	/**
	 */
	const POST_UPDATE = 'core.module.post_update';

	/**
	 */
	const PRE_ACTIONS_ADD = 'core.module.pre_actions_add';

	/**
	 */
	const PRE_ACTIONS_REMOVE = 'core.module.pre_actions_add';

	/**
	 */
	const PRE_ACTIONS_UPDATE = 'core.module.pre_actions_update';

	/**
	 */
	const PRE_CREATE = 'core.module.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.module.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.module.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.module.pre_update';

	/**
	 */
	protected $module;

	/**
	 * @param Module $module
	 */
	public function __construct(Module $module) {
		$this->module = $module;
	}

	/**
	 * @return Module
	 */
	public function getModule() {
		return $this->module;
	}
}
