<?php
namespace keeko\core\event;

use keeko\core\model\Localization;

/**
 */
class LocalizationEvent {

	/**
	 */
	const POST_CREATE = 'core.localization.post_create';

	/**
	 */
	const POST_DELETE = 'core.localization.post_delete';

	/**
	 */
	const POST_EXT_LANG_UPDATE = 'core.localization.post_ext_lang_update';

	/**
	 */
	const POST_LANGUAGE_VARIANT_ADD = 'core.localization.post_language_variant_add';

	/**
	 */
	const POST_LANGUAGE_VARIANT_REMOVE = 'core.localization.post_language_variant_add';

	/**
	 */
	const POST_LANGUAGE_VARIANT_UPDATE = 'core.localization.post_language_variant_update';

	/**
	 */
	const POST_PARENT_UPDATE = 'core.localization.post_parent_update';

	/**
	 */
	const POST_SAVE = 'core.localization.post_save';

	/**
	 */
	const POST_SCRIPT_UPDATE = 'core.localization.post_script_update';

	/**
	 */
	const POST_UPDATE = 'core.localization.post_update';

	/**
	 */
	const PRE_CREATE = 'core.localization.pre_create';

	/**
	 */
	const PRE_DELETE = 'core.localization.pre_delete';

	/**
	 */
	const PRE_EXT_LANG_UPDATE = 'core.localization.pre_ext_lang_update';

	/**
	 */
	const PRE_LANGUAGE_VARIANT_ADD = 'core.localization.pre_language_variant_add';

	/**
	 */
	const PRE_LANGUAGE_VARIANT_REMOVE = 'core.localization.pre_language_variant_add';

	/**
	 */
	const PRE_LANGUAGE_VARIANT_UPDATE = 'core.localization.pre_language_variant_update';

	/**
	 */
	const PRE_PARENT_UPDATE = 'core.localization.pre_parent_update';

	/**
	 */
	const PRE_SAVE = 'core.localization.pre_save';

	/**
	 */
	const PRE_SCRIPT_UPDATE = 'core.localization.pre_script_update';

	/**
	 */
	const PRE_UPDATE = 'core.localization.pre_update';

	/**
	 * @var keeko.core.model
	 */
	protected $localization;

	/**
	 * @param Localization $localization
	 */
	public function __construct(Localization $localization) {
		$this->localization = localization;
	}

	/**
	 * @return Localization
	 */
	public function getLocalization() {
		return $this->localization;
	}
}
