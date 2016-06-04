<?php
namespace keeko\core\serializer\base;

use keeko\core\serializer\TypeInferencer;

/**
 */
trait CountrySerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'numeric' => $model->getNumeric(),
			'alpha-2' => $model->getAlpha2(),
			'alpha-3' => $model->getAlpha3(),
			'short-name' => $model->getShortName(),
			'ioc' => $model->getIoc(),
			'tld' => $model->getTld(),
			'phone' => $model->getPhone(),
			'capital' => $model->getCapital(),
			'postal-code-format' => $model->getPostalCodeFormat(),
			'postal-code-regex' => $model->getPostalCodeRegex(),
			'formal-name' => $model->getFormalName(),
			'formal-native-name' => $model->getFormalNativeName(),
			'short-native-name' => $model->getShortNativeName(),
			'bbox-sw-lat' => $model->getBboxSwLat(),
			'bbox-sw-lng' => $model->getBboxSwLng(),
			'bbox-ne-lat' => $model->getBboxNeLat(),
			'bbox-ne-lng' => $model->getBboxNeLng()
		];
	}

	/**
	 */
	public function getFields() {
		return ['numeric', 'alpha-2', 'alpha-3', 'short-name', 'ioc', 'tld', 'phone', 'capital', 'postal-code-format', 'postal-code-regex', 'formal-name', 'formal-native-name', 'short-native-name', 'bbox-sw-lat', 'bbox-sw-lng', 'bbox-ne-lat', 'bbox-ne-lng'];
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
		return ['numeric', 'alpha-2', 'alpha-3', 'short-name', 'ioc', 'tld', 'phone', 'capital', 'postal-code-format', 'postal-code-regex', 'formal-name', 'formal-native-name', 'short-native-name', 'bbox-sw-lat', 'bbox-sw-lng', 'bbox-ne-lat', 'bbox-ne-lng'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/country';
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
