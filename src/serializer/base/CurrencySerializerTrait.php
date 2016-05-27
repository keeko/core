<?php
namespace keeko\core\serializer\base;

/**
 */
trait CurrencySerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'numeric' => $model->getNumeric(),
			'alpha-3' => $model->getAlpha3(),
			'name' => $model->getName(),
			'symbol-left' => $model->getSymbolLeft(),
			'symbol-right' => $model->getSymbolRight(),
			'decimal-digits' => $model->getDecimalDigits(),
			'sub-divisor' => $model->getSubDivisor(),
			'sub-symbol-left' => $model->getSubSymbolLeft(),
			'sub-symbol-right' => $model->getSubSymbolRight()
		];
	}

	/**
	 */
	public function getFields() {
		return ['numeric', 'alpha-3', 'name', 'symbol-left', 'symbol-right', 'decimal-digits', 'sub-divisor', 'sub-symbol-left', 'sub-symbol-right'];
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
		return ['numeric', 'alpha-3', 'name', 'symbol-left', 'symbol-right', 'decimal-digits', 'sub-divisor', 'sub-symbol-left', 'sub-symbol-right'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/currency';
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
