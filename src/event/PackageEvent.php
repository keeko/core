<?php
namespace keeko\core\event;

use keeko\core\model\Package;

/**
 */
class PackageEvent {

	/**
	 */
	const POST_CREATE = 'core.package.post_create';

	/**
	 */
	const POST_DELETE = 'core.package.post_delete';

	/**
	 */
	const POST_SAVE = 'core.package.post_save';

	/**
	 */
	const POST_UPDATE = 'core.package.post_update';

	/**
	 */
	const PRE_CREATE = 'core.package.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.package.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.package.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.package.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $package;

	/**
	 * @param Package $package
	 */
	public function __construct(Package $package) {
		$this->package = package;
	}

	/**
	 * @return Package
	 */
	public function getPackage() {
		return $this->package;
	}
}
