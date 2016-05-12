<?php
namespace keeko\core\event;

use keeko\core\model\ApplicationUri;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class ApplicationUriEvent extends Event {

	/**
	 */
	const POST_APPLICATION_UPDATE = 'core.application_uri.post_application_update';

	/**
	 */
	const POST_CREATE = 'core.application_uri.post_create';

	/**
	 */
	const POST_DELETE = 'core.application_uri.post_delete';

	/**
	 */
	const POST_LOCALIZATION_UPDATE = 'core.application_uri.post_localization_update';

	/**
	 */
	const POST_SAVE = 'core.application_uri.post_save';

	/**
	 */
	const POST_UPDATE = 'core.application_uri.post_update';

	/**
	 */
	const PRE_APPLICATION_UPDATE = 'core.application_uri.pre_application_update';

	/**
	 */
	const PRE_CREATE = 'core.application_uri.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.application_uri.pre_delete';

	/**
	 */
	const PRE_LOCALIZATION_UPDATE = 'core.application_uri.pre_localization_update';

	/**
	 */
	const PRE_SAVE = 'core.application_uri.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.application_uri.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $applicationUri;

	/**
	 * @param ApplicationUri $applicationUri
	 */
	public function __construct(ApplicationUri $applicationUri) {
		$this->applicationUri = $applicationUri;
	}

	/**
	 * @return ApplicationUri
	 */
	public function getApplicationUri() {
		return $this->applicationUri;
	}
}
