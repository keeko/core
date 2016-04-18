<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;

/**
 */
trait ModuleSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'class_name' => $model->ClassName(),
			'activated_version' => $model->ActivatedVersion(),
			'default_action' => $model->DefaultAction(),
			'slug' => $model->Slug(),
			'has_api' => $model->Api(),
			'id' => $model->Id(),
			'name' => $model->Name(),
			'title' => $model->Title(),
			'description' => $model->Description(),
			'installed_version' => $model->InstalledVersion(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['class_name', 'activated_version', 'default_action', 'slug', 'has_api', 'id', 'name', 'title', 'description', 'installed_version'];
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
		return ['class_name', 'activated_version', 'default_action', 'slug', 'has_api', 'id', 'name', 'title', 'description', 'installed_version'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/module';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['class_name', 'activated_version', 'default_action', 'slug', 'has_api', 'id', 'name', 'title', 'description', 'installed_version']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}
}
