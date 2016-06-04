<?php
namespace keeko\core\serializer\base;

use keeko\core\serializer\TypeInferencer;
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
			'value' => $model->getValue(),
			'module-id' => $model->getModuleId()
		];
	}

	/**
	 */
	public function getFields() {
		return ['value', 'module-id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getId($model) {
		if ($model !== null) {
			return $model->getId();
		}

		return null;
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
		return ['value', 'module-id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/preference';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['key', 'value', 'module-id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 */
	protected function getTypeInferencer() {
		return TypeInferencer::getInstance();
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return void
	 */
	abstract protected function hydrateRelationships($model, $data);
}
