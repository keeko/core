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
			'id' => $model->getId(),
			'alpha_4' => $model->getAlpha4(),
			'numeric' => $model->getNumeric(),
			'name' => $model->getName(),
			'alias' => $model->getAlias(),
			'direction' => $model->getDirection(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'alpha_4', 'numeric', 'name', 'alias', 'direction'];
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
		return ['id', 'alpha_4', 'numeric', 'name', 'alias', 'direction'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/language-script';
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
