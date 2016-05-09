<?php
namespace keeko\core\event;

use keeko\core\model\Activity;

/**
 */
class ActivityEvent {

	/**
	 */
	const POST_ACTOR_UPDATE = 'core.activity.post_actor_update';

	/**
	 */
	const POST_CREATE = 'core.activity.post_create';

	/**
	 */
	const POST_DELETE = 'core.activity.post_delete';

	/**
	 */
	const POST_SAVE = 'core.activity.post_save';

	/**
	 */
	const POST_TARGET_UPDATE = 'core.activity.post_target_update';

	/**
	 */
	const POST_UPDATE = 'core.activity.post_update';

	/**
	 */
	const PRE_ACTOR_UPDATE = 'core.activity.pre_actor_update';

	/**
	 */
	const PRE_CREATE = 'core.activity.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.activity.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.activity.pre_save';

	/**
	 */
	const PRE_TARGET_UPDATE = 'core.activity.pre_target_update';

	/**
	 */
	const PRE_UPDATE = 'core.activity.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $activity;

	/**
	 * @param Activity $activity
	 */
	public function __construct(Activity $activity) {
		$this->activity = activity;
	}

	/**
	 * @return Activity
	 */
	public function getActivity() {
		return $this->activity;
	}
}
