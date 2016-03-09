<?php
namespace keeko\core\serializer\base;

/**
 */
trait LanguageScriptSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'alpha_4' => $model->Alpha4(),
			'numeric' => $model->Numeric(),
			'name' => $model->Name(),
			'alias' => $model->Alias(),
			'direction' => $model->Direction(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'alpha_4', 'numeric', 'name', 'alias', 'direction'];
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
		return ['id', 'alpha_4', 'numeric', 'name', 'alias', 'direction'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/language-script';
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
