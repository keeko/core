<?php
namespace keeko\core\serializer\base;

/**
 */
trait LanguageFamilySerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'parent_id' => $model->ParentId(),
			'alpha_3' => $model->Alpha3(),
			'name' => $model->Name(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'parent_id', 'alpha_3', 'name'];
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
		return ['id', 'parent_id', 'alpha_3', 'name'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/language-family';
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
