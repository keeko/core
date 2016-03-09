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
			'id' => $model->Id(),
			'name' => $model->Name(),
			'area_id' => $model->AreaId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'area_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getId($model) {
		return $model->getId();
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'name', 'area_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/region-type';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// this model is read-only!
		return $model;
	}
}
