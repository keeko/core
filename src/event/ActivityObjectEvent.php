<?php
namespace keeko\core\event;

use keeko\core\model\ActivityObject;

/**
 */
class ActivityObjectEvent {

	/**
	 */
	const POST_CREATE = 'core.activity_object.post_create';

	/**
	 */
	const POST_DELETE = 'core.activity_object.post_delete';

	/**
	 */
	const POST_SAVE = 'core.activity_object.post_save';

	/**
	 */
	const POST_UPDATE = 'core.activity_object.post_update';

	/**
	 */
	const PRE_CREATE = 'core.activity_object.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.activity_object.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.activity_object.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.activity_object.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $activityObject;

	/**
	 * @param ActivityObject $activityObject
	 */
	public function __construct(ActivityObject $activityObject) {
		$this->activityObject = activityObject;
	}

	/**
	 * @return ActivityObject
	 */
	public function getActivityObject() {
		return $this->activityObject;
	}
}
