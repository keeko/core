<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;

/**
 */
trait ApplicationSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'class_name' => $model->getClassName(),
			'id' => $model->getId(),
			'name' => $model->getName(),
			'title' => $model->getTitle(),
			'description' => $model->getDescription(),
			'installed_version' => $model->getInstalledVersion(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['class_name', 'id', 'name', 'title', 'description', 'installed_version'];
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
	public function getRelationships() {
		return [
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['class_name', 'id', 'name', 'title', 'description', 'installed_version'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/application';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['class_name', 'id', 'name', 'title', 'description', 'installed_version']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return void
	 */
	abstract protected function hydrateRelationships($model, $data);
}
