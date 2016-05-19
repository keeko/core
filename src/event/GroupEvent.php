<?php
namespace keeko\core\event;

use keeko\core\model\Group;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class GroupEvent extends Event {

	/**
	 */
	const POST_ACTIONS_ADD = 'core.group.post_actions_add';

	/**
	 */
	const POST_ACTIONS_REMOVE = 'core.group.post_actions_add';

	/**
	 */
	const POST_ACTIONS_UPDATE = 'core.group.post_actions_update';

	/**
	 */
	const POST_CREATE = 'core.group.post_create';

	/**
	 */
	const POST_DELETE = 'core.group.post_delete';

	/**
	 */
	const POST_SAVE = 'core.group.post_save';

	/**
	 */
	const POST_UPDATE = 'core.group.post_update';

	/**
	 */
	const POST_USERS_ADD = 'core.group.post_users_add';

	/**
	 */
	const POST_USERS_REMOVE = 'core.group.post_users_add';

	/**
	 */
	const POST_USERS_UPDATE = 'core.group.post_users_update';

	/**
	 */
	const PRE_ACTIONS_ADD = 'core.group.pre_actions_add';

	/**
	 */
	const PRE_ACTIONS_REMOVE = 'core.group.pre_actions_add';

	/**
	 */
	const PRE_ACTIONS_UPDATE = 'core.group.pre_actions_update';

	/**
	 */
	const PRE_CREATE = 'core.group.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.group.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.group.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.group.pre_update';

	/**
	 */
	const PRE_USERS_ADD = 'core.group.pre_users_add';

	/**
	 */
	const PRE_USERS_REMOVE = 'core.group.pre_users_add';

	/**
	 */
	const PRE_USERS_UPDATE = 'core.group.pre_users_update';

	/**
	 */
	protected $group;

	/**
	 * @param Group $group
	 */
	public function __construct(Group $group) {
		$this->group = $group;
	}

	/**
	 * @return Group
	 */
	public function getGroup() {
		return $this->group;
	}
}
