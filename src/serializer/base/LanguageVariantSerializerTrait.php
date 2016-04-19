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
			'id' => $model->getId(),
			'name' => $model->getName(),
			'subtag' => $model->getSubtag(),
			'prefixes' => $model->getPrefixes(),
			'comment' => $model->getComment(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'subtag', 'prefixes', 'comment'];
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
		return ['id', 'name', 'subtag', 'prefixes', 'comment'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/language-variant';
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
