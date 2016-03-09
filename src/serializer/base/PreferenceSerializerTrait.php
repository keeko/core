<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;

/**
 */
trait PreferenceSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'key' => $model->Key(),
			'value' => $model->Value(),
			'module_id' => $model->ModuleId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['key', 'value', 'module_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getId($model) {
		return $model->getId();
	}

	/**
	 */
	public function getRelationships() {
		return [
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['key', 'value', 'module_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/preference';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['key', 'value', 'module_id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}
}
