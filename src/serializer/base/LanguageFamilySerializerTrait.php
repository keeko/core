<?php
namespace keeko\core\serializer\base;

use keeko\core\serializer\TypeInferencer;

/**
 */
trait LanguageFamilySerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'parent-id' => $model->getParentId(),
			'alpha-3' => $model->getAlpha3(),
			'name' => $model->getName()
		];
	}

	/**
	 */
	public function getFields() {
		return ['parent-id', 'alpha-3', 'name'];
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
		return ['parent-id', 'alpha-3', 'name'];
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

	/**
	 */
	protected function getTypeInferencer() {
		return TypeInferencer::getInstance();
	}
}
