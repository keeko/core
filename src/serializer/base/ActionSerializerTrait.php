<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Module;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Group;
use Tobscure\JsonApi\Collection;
use keeko\core\model\Api;

/**
 */
trait ActionSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function apis($model) {
		$relationship = new Relationship(new Collection($model->getApis(), Api::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'api');
	}

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
			'class-name' => $model->getClassName(),
			'module-id' => $model->getModuleId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'name', 'title', 'description', 'class-name', 'module-id'];
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
			'module' => Module::getSerializer()->getType(null),
			'groups' => Group::getSerializer()->getType(null),
			'apis' => Api::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'name', 'title', 'description', 'class-name', 'module-id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/action';
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function groups($model) {
		$relationship = new Relationship(new Collection($model->getGroups(), Group::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'group');
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'name', 'title', 'description', 'class-name', 'module-id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function module($model) {
		$serializer = Module::getSerializer();
		$relationship = new Relationship(new Resource($model->getModule(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'module');
	}

	/**
	 * @param Relationship $relationship
	 * @param mixed $model
	 * @param string $related
	 * @return Relationship
	 */
	abstract protected function addRelationshipSelfLink(Relationship $relationship, $model, $related);

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return void
	 */
	abstract protected function hydrateRelationships($model, $data);
}
