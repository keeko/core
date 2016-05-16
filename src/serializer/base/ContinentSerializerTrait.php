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
			'id' => $model->getId(),
			'parent-id' => $model->getParentId(),
			'numeric' => $model->getNumeric(),
			'name' => $model->getName(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'parent-id', 'numeric', 'name'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getId($model) {
		return $model->getId();
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'parent-id', 'numeric', 'name'];
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
