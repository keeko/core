<?php
namespace keeko\core\serializer\base;

/**
 */
trait LanguageVariantSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'name' => $model->Name(),
			'subtag' => $model->Subtag(),
			'prefixes' => $model->Prefixes(),
			'comment' => $model->Comment(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'subtag', 'prefixes', 'comment'];
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
		return ['id', 'name', 'subtag', 'prefixes', 'comment'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/language-variant';
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
