<?php
namespace keeko\core\event;

use keeko\core\model\User;

/**
 */
class UserEvent {

	/**
	 */
	const POST_CREATE = 'core.user.post_create';

	/**
	 */
	const POST_DELETE = 'core.user.post_delete';

	/**
	 */
	const POST_GROUP_ADD = 'core.user.post_group_add';

	/**
	 */
	const POST_GROUP_REMOVE = 'core.user.post_group_add';

	/**
	 */
	const POST_GROUP_UPDATE = 'core.user.post_group_update';

	/**
	 */
	const POST_SAVE = 'core.user.post_save';

	/**
	 */
	const POST_UPDATE = 'core.user.post_update';

	/**
	 */
	const PRE_CREATE = 'core.user.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.user.pre_delete';

	/**
	 */
	const PRE_GROUP_ADD = 'core.user.pre_group_add';

	/**
	 */
	const PRE_GROUP_REMOVE = 'core.user.pre_group_add';

	/**
	 */
	const PRE_GROUP_UPDATE = 'core.user.pre_group_update';

	/**
	 */
	const PRE_SAVE = 'core.user.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.user.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $user;

	/**
	 * @param User $user
	 */
	public function __construct(User $user) {
		$this->user = user;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}
}
