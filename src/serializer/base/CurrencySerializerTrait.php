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
			'id' => $model->getId(),
			'numeric' => $model->getNumeric(),
			'alpha_3' => $model->getAlpha3(),
			'name' => $model->getName(),
			'symbol_left' => $model->getSymbolLeft(),
			'symbol_right' => $model->getSymbolRight(),
			'decimal_digits' => $model->getDecimalDigits(),
			'sub_divisor' => $model->getSubDivisor(),
			'sub_symbol_left' => $model->getSubSymbolLeft(),
			'sub_symbol_right' => $model->getSubSymbolRight(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'numeric', 'alpha_3', 'name', 'symbol_left', 'symbol_right', 'decimal_digits', 'sub_divisor', 'sub_symbol_left', 'sub_symbol_right'];
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
		return ['id', 'numeric', 'alpha_3', 'name', 'symbol_left', 'symbol_right', 'decimal_digits', 'sub_divisor', 'sub_symbol_left', 'sub_symbol_right'];
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
