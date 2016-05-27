<?php
namespace keeko\core\serializer\base;

/**
 */
trait ContinentSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'parent-id' => $model->getParentId(),
			'numeric' => $model->getNumeric(),
			'name' => $model->getName()
		];
	}

	/**
	 */
	public function getFields() {
		return ['parent-id', 'numeric', 'name'];
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
	public function getSortFields() {
		return ['parent-id', 'numeric', 'name'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/continent';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// this model is read-only!
		return $model;
	}
}
