<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;

/**
 */
trait PackageSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'name' => $model->getName(),
			'title' => $model->getTitle(),
			'description' => $model->getDescription(),
			'installed_version' => $model->getInstalledVersion(),
			'descendant_class' => $model->getDescendantClass(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'title', 'description', 'installed_version', 'descendant_class'];
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
		return ['id', 'name', 'title', 'description', 'installed_version', 'descendant_class'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/package';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'name', 'title', 'description', 'installed_version', 'descendant_class']);

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
