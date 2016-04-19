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
			'id' => $model->getId(),
			'parent_id' => $model->getParentId(),
			'alpha_3' => $model->getAlpha3(),
			'name' => $model->getName(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'parent_id', 'alpha_3', 'name'];
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
		return ['id', 'parent_id', 'alpha_3', 'name'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/language-family';
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
