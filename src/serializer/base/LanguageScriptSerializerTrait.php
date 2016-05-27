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
			'alpha-4' => $model->getAlpha4(),
			'numeric' => $model->getNumeric(),
			'name' => $model->getName(),
			'alias' => $model->getAlias(),
			'direction' => $model->getDirection()
		];
	}

	/**
	 */
	public function getFields() {
		return ['alpha-4', 'numeric', 'name', 'alias', 'direction'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getId($model) {
		if ($model !== null) {
			return $model->getId();
		}

		return null;
	}

	/**
	 */
	public function getSortFields() {
		return ['alpha-4', 'numeric', 'name', 'alias', 'direction'];
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
