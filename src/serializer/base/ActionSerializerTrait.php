<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Module;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Group;
use Tobscure\JsonApi\Collection;

/**
 */
trait ActionSerializerTrait {

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
			'class_name' => $model->ClassName(),
			'module_id' => $model->ModuleId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'title', 'description', 'class_name', 'module_id'];
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
			'module' => Module::getSerializer()->getType(null),
			'group' => Group::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'name', 'title', 'description', 'class_name', 'module_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/action';
	}

	/**
	 * @param mixed $model
	 */
	public function group($model) {
		$relationship = new Relationship(new Collection($model->getGroups(), Group::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'group');
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'name', 'title', 'description', 'class_name', 'module_id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 */
	public function module($model) {
		$serializer = Module::getSerializer();
		$relationship = new Relationship(new Resource($model->getModule(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'module');
	}
}
