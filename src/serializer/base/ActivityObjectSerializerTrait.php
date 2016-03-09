<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;

/**
 */
trait ActivityObjectSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'class_name' => $model->ClassName(),
			'type' => $model->Type(),
			'display_name' => $model->DisplayName(),
			'url' => $model->Url(),
			'reference_id' => $model->ReferenceId(),
			'version' => $model->Version(),
			'extra' => $model->Extra(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'class_name', 'type', 'display_name', 'url', 'reference_id', 'version', 'extra'];
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
		return ['id', 'class_name', 'type', 'display_name', 'url', 'reference_id', 'version', 'extra'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/activity-object';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'class_name', 'type', 'display_name', 'url', 'reference_id', 'version', 'extra']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}
}
