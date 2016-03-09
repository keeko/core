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
			'id' => $model->Id(),
			'code' => $model->Code(),
			'name' => $model->Name(),
			'native_name' => $model->NativeName(),
			'alt_names' => $model->AltNames(),
			'parent_id' => $model->ParentId(),
			'country_id' => $model->CountryId(),
			'type_id' => $model->TypeId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'code', 'name', 'native_name', 'alt_names', 'parent_id', 'country_id', 'type_id'];
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
		return ['id', 'code', 'name', 'native_name', 'alt_names', 'parent_id', 'country_id', 'type_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/subdivision';
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
