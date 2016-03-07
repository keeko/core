<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;

/**
 */
class CountrySerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'numeric' => $model->Numeric(),
			'alpha_2' => $model->Alpha2(),
			'alpha_3' => $model->Alpha3(),
			'short_name' => $model->ShortName(),
			'ioc' => $model->Ioc(),
			'tld' => $model->Tld(),
			'phone' => $model->Phone(),
			'capital' => $model->Capital(),
			'postal_code_format' => $model->PostalCodeFormat(),
			'postal_code_regex' => $model->PostalCodeRegex(),
			'continent_id' => $model->ContinentId(),
			'currency_id' => $model->CurrencyId(),
			'type_id' => $model->TypeId(),
			'subtype_id' => $model->SubtypeId(),
			'sovereignity_id' => $model->SovereignityId(),
			'formal_name' => $model->FormalName(),
			'formal_native_name' => $model->FormalNativeName(),
			'short_native_name' => $model->ShortNativeName(),
			'bbox_sw_lat' => $model->BboxSwLat(),
			'bbox_sw_lng' => $model->BboxSwLng(),
			'bbox_ne_lat' => $model->BboxNeLat(),
			'bbox_ne_lng' => $model->BboxNeLng(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'numeric', 'alpha_2', 'alpha_3', 'short_name', 'ioc', 'tld', 'phone', 'capital', 'postal_code_format', 'postal_code_regex', 'continent_id', 'currency_id', 'type_id', 'subtype_id', 'sovereignity_id', 'formal_name', 'formal_native_name', 'short_native_name', 'bbox_sw_lat', 'bbox_sw_lng', 'bbox_ne_lat', 'bbox_ne_lng'];
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
		return ['id', 'numeric', 'alpha_2', 'alpha_3', 'short_name', 'ioc', 'tld', 'phone', 'capital', 'postal_code_format', 'postal_code_regex', 'continent_id', 'currency_id', 'type_id', 'subtype_id', 'sovereignity_id', 'formal_name', 'formal_native_name', 'short_native_name', 'bbox_sw_lat', 'bbox_sw_lng', 'bbox_ne_lat', 'bbox_ne_lng'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/country';
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
