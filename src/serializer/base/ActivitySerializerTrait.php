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
	 * @param mixed $related
	 */
	public function actor($model, $related) {
		$serializer = User::getSerializer();
		$relationship = new Relationship(new Resource($model->getActor(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'actor_id' => $model->ActorId(),
			'verb' => $model->Verb(),
			'object_id' => $model->ObjectId(),
			'target_id' => $model->TargetId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'actor_id', 'verb', 'object_id', 'target_id'];
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
			'actor' => User::getSerializer()->getType(null),
			'object' => ActivityObject::getSerializer()->getType(null),
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
	 */
	public function getType($model) {
		return 'core/activity';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
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
	 * @param mixed $related
	 */
	public function object($model, $related) {
		$serializer = ActivityObject::getSerializer();
		$relationship = new Relationship(new Resource($model->getObject(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setActor($model, $data) {
		$model->setActorId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setObject($model, $data) {
		$model->setObjectId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setTarget($model, $data) {
		$model->setTargetId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function target($model, $related) {
		$serializer = ActivityObject::getSerializer();
		$relationship = new Relationship(new Resource($model->getTarget(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}
}
