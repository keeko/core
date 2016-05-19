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
			'native-name' => $model->getNativeName(),
			'alt-names' => $model->getAltNames(),
			'parent-id' => $model->getParentId(),
			'country-id' => $model->getCountryId(),
			'type-id' => $model->getTypeId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'code', 'name', 'native-name', 'alt-names', 'parent-id', 'country-id', 'type-id'];
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
		return ['id', 'code', 'name', 'native-name', 'alt-names', 'parent-id', 'country-id', 'type-id'];
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
