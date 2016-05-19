<?php
namespace keeko\core\event;

use keeko\core\model\Api;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class ApiEvent extends Event {

	/**
	 */
	const POST_ACTION_UPDATE = 'core.api.post_action_update';

	/**
	 */
	const POST_CREATE = 'core.api.post_create';

	/**
	 */
	const POST_DELETE = 'core.api.post_delete';

	/**
	 */
	const POST_SAVE = 'core.api.post_save';

	/**
	 */
	const POST_UPDATE = 'core.api.post_update';

	/**
	 */
	const PRE_ACTION_UPDATE = 'core.api.pre_action_update';

	/**
	 */
	const PRE_CREATE = 'core.api.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.api.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.api.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.api.pre_update';

	/**
	 */
	protected $api;

	/**
	 * @param Api $api
	 */
	public function __construct(Api $api) {
		$this->api = $api;
	}

	/**
	 * @return Api
	 */
	public function getApi() {
		return $this->api;
	}
}
