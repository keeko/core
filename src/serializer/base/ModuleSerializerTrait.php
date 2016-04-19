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
			'class_name' => $model->getClassName(),
			'activated_version' => $model->getActivatedVersion(),
			'default_action' => $model->getDefaultAction(),
			'slug' => $model->getSlug(),
			'has_api' => $model->getApi(),
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
		return ['class_name', 'activated_version', 'default_action', 'slug', 'has_api', 'id', 'name', 'title', 'description', 'installed_version'];
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
		return ['class_name', 'activated_version', 'default_action', 'slug', 'has_api', 'id', 'name', 'title', 'description', 'installed_version'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/module';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['class_name', 'activated_version', 'default_action', 'slug', 'has_api', 'id', 'name', 'title', 'description', 'installed_version']);

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
