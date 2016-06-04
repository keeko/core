<?php
namespace keeko\core\serializer\base;

use keeko\core\serializer\TypeInferencer;

/**
 */
trait LanguageSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'alpha-2' => $model->getAlpha2(),
			'alpha-3-t' => $model->getAlpha3T(),
			'alpha-3-b' => $model->getAlpha3B(),
			'alpha-3' => $model->getAlpha3(),
			'macrolanguage-status' => $model->getMacrolanguageStatus(),
			'name' => $model->getName(),
			'native-name' => $model->getNativeName(),
			'collate' => $model->getCollate(),
			'subtag' => $model->getSubtag(),
			'prefix' => $model->getPrefix()
		];
	}

	/**
	 */
	public function getFields() {
		return ['alpha-2', 'alpha-3-t', 'alpha-3-b', 'alpha-3', 'macrolanguage-status', 'name', 'native-name', 'collate', 'subtag', 'prefix'];
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
		return ['alpha-2', 'alpha-3-t', 'alpha-3-b', 'alpha-3', 'macrolanguage-status', 'name', 'native-name', 'collate', 'subtag', 'prefix'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/language';
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
