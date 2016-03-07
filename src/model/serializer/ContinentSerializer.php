<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;

/**
 */
class ContinentSerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'parent_id' => $model->ParentId(),
			'numeric' => $model->Numeric(),
			'name' => $model->Name(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'parent_id', 'numeric', 'name'];
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
		return ['id', 'parent_id', 'numeric', 'name'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/continent';
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
