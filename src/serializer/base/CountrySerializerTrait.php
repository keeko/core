<?php
namespace keeko\core\serializer\base;

/**
 */
trait CountrySerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'numeric' => $model->getNumeric(),
			'alpha_2' => $model->getAlpha2(),
			'alpha_3' => $model->getAlpha3(),
			'short_name' => $model->getShortName(),
			'ioc' => $model->getIoc(),
			'tld' => $model->getTld(),
			'phone' => $model->getPhone(),
			'capital' => $model->getCapital(),
			'postal_code_format' => $model->getPostalCodeFormat(),
			'postal_code_regex' => $model->getPostalCodeRegex(),
			'continent_id' => $model->getContinentId(),
			'currency_id' => $model->getCurrencyId(),
			'type_id' => $model->getTypeId(),
			'subtype_id' => $model->getSubtypeId(),
			'sovereignity_id' => $model->getSovereignityId(),
			'formal_name' => $model->getFormalName(),
			'formal_native_name' => $model->getFormalNativeName(),
			'short_native_name' => $model->getShortNativeName(),
			'bbox_sw_lat' => $model->getBboxSwLat(),
			'bbox_sw_lng' => $model->getBboxSwLng(),
			'bbox_ne_lat' => $model->getBboxNeLat(),
			'bbox_ne_lng' => $model->getBboxNeLng(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'numeric', 'alpha_2', 'alpha_3', 'short_name', 'ioc', 'tld', 'phone', 'capital', 'postal_code_format', 'postal_code_regex', 'continent_id', 'currency_id', 'type_id', 'subtype_id', 'sovereignity_id', 'formal_name', 'formal_native_name', 'short_native_name', 'bbox_sw_lat', 'bbox_sw_lng', 'bbox_ne_lat', 'bbox_ne_lng'];
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
		return ['id', 'numeric', 'alpha_2', 'alpha_3', 'short_name', 'ioc', 'tld', 'phone', 'capital', 'postal_code_format', 'postal_code_regex', 'continent_id', 'currency_id', 'type_id', 'subtype_id', 'sovereignity_id', 'formal_name', 'formal_native_name', 'short_native_name', 'bbox_sw_lat', 'bbox_sw_lng', 'bbox_ne_lat', 'bbox_ne_lng'];
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
}
