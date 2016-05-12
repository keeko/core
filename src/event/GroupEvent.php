<?php
namespace keeko\core\event;

use keeko\core\model\Group;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class GroupEvent extends Event {

	/**
	 */
	const POST_ACTION_ADD = 'core.group.post_action_add';

	/**
	 */
	const POST_ACTION_REMOVE = 'core.group.post_action_add';

	/**
	 */
	const POST_ACTION_UPDATE = 'core.group.post_action_update';

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
	const POST_USER_ADD = 'core.group.post_user_add';

	/**
	 */
	const POST_USER_REMOVE = 'core.group.post_user_add';

	/**
	 */
	const POST_USER_UPDATE = 'core.group.post_user_update';

	/**
	 */
	const PRE_ACTION_ADD = 'core.group.pre_action_add';

	/**
	 */
	const PRE_ACTION_REMOVE = 'core.group.pre_action_add';

	/**
	 */
	const PRE_ACTION_UPDATE = 'core.group.pre_action_update';

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
	const PRE_USER_ADD = 'core.group.pre_user_add';

	/**
	 */
	const PRE_USER_REMOVE = 'core.group.pre_user_add';

	/**
	 */
	const PRE_USER_UPDATE = 'core.group.pre_user_update';

	/**
	 * @var keeko.core.model
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
