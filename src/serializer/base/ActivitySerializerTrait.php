<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\User;
use Tobscure\JsonApi\Resource;
use keeko\core\model\ActivityObject;

/**
 */
trait ActivitySerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function actor($model) {
		$serializer = User::getSerializer();
		$relationship = new Relationship(new Resource($model->getActor(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'actor');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'actor_id' => $model->getActorId(),
			'verb' => $model->getVerb(),
			'object_id' => $model->getObjectId(),
			'target_id' => $model->getTargetId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'actor_id', 'verb', 'object_id', 'target_id'];
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
			'actor' => User::getSerializer()->getType(null),
			'target' => ActivityObject::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'actor_id', 'verb', 'object_id', 'target_id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/activity';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'actor_id', 'verb', 'object_id', 'target_id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function target($model) {
		$serializer = ActivityObject::getSerializer();
		$relationship = new Relationship(new Resource($model->getTarget(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'target');
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	abstract protected function hydrateRelationships($model, $data);
}
