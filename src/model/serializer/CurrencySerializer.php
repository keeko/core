<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;

/**
 */
class CurrencySerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'numeric' => $model->Numeric(),
			'alpha_3' => $model->Alpha3(),
			'name' => $model->Name(),
			'symbol_left' => $model->SymbolLeft(),
			'symbol_right' => $model->SymbolRight(),
			'decimal_digits' => $model->DecimalDigits(),
			'sub_divisor' => $model->SubDivisor(),
			'sub_symbol_left' => $model->SubSymbolLeft(),
			'sub_symbol_right' => $model->SubSymbolRight(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'numeric', 'alpha_3', 'name', 'symbol_left', 'symbol_right', 'decimal_digits', 'sub_divisor', 'sub_symbol_left', 'sub_symbol_right'];
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
		return ['id', 'numeric', 'alpha_3', 'name', 'symbol_left', 'symbol_right', 'decimal_digits', 'sub_divisor', 'sub_symbol_left', 'sub_symbol_right'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/currency';
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
