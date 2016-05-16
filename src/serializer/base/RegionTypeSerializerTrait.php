<?php
namespace keeko\core\serializer\base;

/**
 */
trait RegionTypeSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'name' => $model->getName(),
			'area-id' => $model->getAreaId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'area-id'];
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
		return ['id', 'name', 'area-id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/region-type';
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
