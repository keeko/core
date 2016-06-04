<?php
namespace keeko\core\serializer\base;

use keeko\core\serializer\TypeInferencer;

/**
 */
trait SubdivisionSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'code' => $model->getCode(),
			'name' => $model->getName(),
			'native-name' => $model->getNativeName(),
			'alt-names' => $model->getAltNames(),
			'parent-id' => $model->getParentId()
		];
	}

	/**
	 */
	public function getFields() {
		return ['code', 'name', 'native-name', 'alt-names', 'parent-id'];
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
		return ['code', 'name', 'native-name', 'alt-names', 'parent-id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/subdivision';
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
