<?php
namespace keeko\core\event;

use keeko\core\model\User;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class UserEvent extends Event {

	/**
	 */
	const POST_ACTIVITIES_ADD = 'core.user.post_activities_add';

	/**
	 */
	const POST_ACTIVITIES_REMOVE = 'core.user.post_activities_add';

	/**
	 */
	const POST_ACTIVITIES_UPDATE = 'core.user.post_activities_update';

	/**
	 */
	const POST_CREATE = 'core.user.post_create';

	/**
	 */
	const POST_DELETE = 'core.user.post_delete';

	/**
	 */
	const POST_GROUPS_ADD = 'core.user.post_groups_add';

	/**
	 */
	const POST_GROUPS_REMOVE = 'core.user.post_groups_add';

	/**
	 */
	const POST_GROUPS_UPDATE = 'core.user.post_groups_update';

	/**
	 */
	const POST_SAVE = 'core.user.post_save';

	/**
	 */
	const POST_SESSIONS_ADD = 'core.user.post_sessions_add';

	/**
	 */
	const POST_SESSIONS_REMOVE = 'core.user.post_sessions_add';

	/**
	 */
	const POST_SESSIONS_UPDATE = 'core.user.post_sessions_update';

	/**
	 */
	const POST_UPDATE = 'core.user.post_update';

	/**
	 */
	const PRE_ACTIVITIES_ADD = 'core.user.pre_activities_add';

	/**
	 */
	const PRE_ACTIVITIES_REMOVE = 'core.user.pre_activities_add';

	/**
	 */
	const PRE_ACTIVITIES_UPDATE = 'core.user.pre_activities_update';

	/**
	 */
	const PRE_CREATE = 'core.user.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.user.pre_delete';

	/**
	 */
	const PRE_GROUPS_ADD = 'core.user.pre_groups_add';

	/**
	 */
	const PRE_GROUPS_REMOVE = 'core.user.pre_groups_add';

	/**
	 */
	const PRE_GROUPS_UPDATE = 'core.user.pre_groups_update';

	/**
	 */
	const PRE_SAVE = 'core.user.pre_save';

	/**
	 */
	const PRE_SESSIONS_ADD = 'core.user.pre_sessions_add';

	/**
	 */
	const PRE_SESSIONS_REMOVE = 'core.user.pre_sessions_add';

	/**
	 */
	const PRE_SESSIONS_UPDATE = 'core.user.pre_sessions_update';

	/**
	 */
	const PRE_UPDATE = 'core.user.pre_update';

	/**
	 */
	protected $user;

	/**
	 * @param User $user
	 */
	public function __construct(User $user) {
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}
}
