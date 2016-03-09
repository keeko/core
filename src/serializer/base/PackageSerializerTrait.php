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
			'id' => $model->Id(),
			'name' => $model->Name(),
			'title' => $model->Title(),
			'description' => $model->Description(),
			'installed_version' => $model->InstalledVersion(),
			'descendant_class' => $model->DescendantClass(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'title', 'description', 'installed_version', 'descendant_class'];
	}

	/**
	 * @param mixed $model
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
	 */
	public function getType($model) {
		return 'core/package';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'name', 'title', 'description', 'installed_version', 'descendant_class']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}
}
