<?php
namespace keeko\core\serializer\base;

use keeko\core\serializer\TypeInferencer;

/**
 */
trait LanguageTypeSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'name' => $model->getName()
		];
	}

	/**
	 */
	public function getFields() {
		return ['name'];
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
		return ['name'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/language-type';
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
