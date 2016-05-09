<?php
namespace keeko\core\event;

use keeko\core\model\Application;

/**
 */
class ApplicationEvent {

	/**
	 */
	const POST_CREATE = 'core.application.post_create';

	/**
	 */
	const POST_DELETE = 'core.application.post_delete';

	/**
	 */
	const POST_SAVE = 'core.application.post_save';

	/**
	 */
	const POST_UPDATE = 'core.application.post_update';

	/**
	 */
	const PRE_CREATE = 'core.application.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.application.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.application.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.application.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $application;

	/**
	 * @param Application $application
	 */
	public function __construct(Application $application) {
		$this->application = application;
	}

	/**
	 * @return Application
	 */
	public function getApplication() {
		return $this->application;
	}
}
