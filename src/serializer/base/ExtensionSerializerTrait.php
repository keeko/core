<?php
namespace keeko\core\serializer\base;

use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;

/**
 */
trait ExtensionSerializerTrait {

	/**
	 */
	private $methodNames = [

	];

	/**
	 */
	private $methodPluralNames = [

	];

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'key' => $model->getKey(),
			'data' => $model->getData()
		];
	}

	/**
	 */
	public function getFields() {
		return ['key', 'data'];
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
	public function getRelationships() {
		return [
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['key', 'data'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/extension';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		// hydrate
		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'key', 'data', 'package-id']);

		// relationships
		//$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $relatedName
	 */
	protected function getCollectionMethodName($relatedName) {
		if (isset($this->methodNames[$relatedName])) {
			return $this->methodNames[$relatedName];
		}
		return null;
	}

	/**
	 * @param mixed $relatedName
	 */
	protected function getCollectionMethodPluralName($relatedName) {
		if (isset($this->methodPluralNames[$relatedName])) {
			return $this->methodPluralNames[$relatedName];
		}
		return null;
	}

	/**
	 */
	protected function getTypeInferencer() {
		return TypeInferencer::getInstance();
	}
}
