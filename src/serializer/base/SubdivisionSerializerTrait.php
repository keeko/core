<?php
namespace keeko\core\serializer\base;

/**
 */
trait SubdivisionSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'code' => $model->getCode(),
			'name' => $model->getName(),
			'native_name' => $model->getNativeName(),
			'alt_names' => $model->getAltNames(),
			'parent_id' => $model->getParentId(),
			'country_id' => $model->getCountryId(),
			'type_id' => $model->getTypeId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'code', 'name', 'native_name', 'alt_names', 'parent_id', 'country_id', 'type_id'];
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
		return ['id', 'code', 'name', 'native_name', 'alt_names', 'parent_id', 'country_id', 'type_id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/subdivision';
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
