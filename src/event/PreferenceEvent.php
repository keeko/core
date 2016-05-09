<?php
namespace keeko\core\event;

use keeko\core\model\Preference;

/**
 */
class PreferenceEvent {

	/**
	 */
	const POST_CREATE = 'core.preference.post_create';

	/**
	 */
	const POST_DELETE = 'core.preference.post_delete';

	/**
	 */
	const POST_SAVE = 'core.preference.post_save';

	/**
	 */
	const POST_UPDATE = 'core.preference.post_update';

	/**
	 */
	const PRE_CREATE = 'core.preference.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.preference.pre_delete';

	/**
	 */
	const PRE_SAVE = 'core.preference.pre_save';

	/**
	 */
	const PRE_UPDATE = 'core.preference.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $preference;

	/**
	 * @param Preference $preference
	 */
	public function __construct(Preference $preference) {
		$this->preference = preference;
	}

	/**
	 * @return Preference
	 */
	public function getPreference() {
		return $this->preference;
	}
}
