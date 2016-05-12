<?php
namespace keeko\core\event;

use keeko\core\model\Extension;
use Symfony\Component\EventDispatcher\Event;

/**
 */
class ExtensionEvent extends Event {

	/**
	 */
	const POST_CREATE = 'core.extension.post_create';

	/**
	 */
	const POST_DELETE = 'core.extension.post_delete';

	/**
	 */
	const POST_PACKAGE_UPDATE = 'core.extension.post_package_update';

	/**
	 */
	const POST_SAVE = 'core.extension.post_save';

	/**
	 */
	const POST_UPDATE = 'core.extension.post_update';

	/**
	 */
	const PRE_CREATE = 'core.extension.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.extension.pre_delete';

	/**
	 */
	const PRE_PACKAGE_UPDATE = 'core.extension.pre_package_update';

	/**
	 */
	const PRE_SAVE = 'core.extension.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.extension.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $extension;

	/**
	 * @param Extension $extension
	 */
	public function __construct(Extension $extension) {
		$this->extension = $extension;
	}

	/**
	 * @return Extension
	 */
	public function getExtension() {
		return $this->extension;
	}
}
