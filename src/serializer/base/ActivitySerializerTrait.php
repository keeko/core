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
		$id = $serializer->getId($model->getActor());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getActor(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'actor');
		}

		return null;
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'verb' => $model->getVerb()
		];
	}

	/**
	 */
	public function getFields() {
		return ['verb'];
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
			'actor' => User::getSerializer()->getType(null),
			'object' => ActivityObject::getSerializer()->getType(null),
			'target' => ActivityObject::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['verb'];
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

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'actor-id', 'verb', 'object-id', 'target-id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function object($model) {
		$serializer = ActivityObject::getSerializer();
		$id = $serializer->getId($model->getObject());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getObject(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'object');
		}

		return null;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function target($model) {
		$serializer = ActivityObject::getSerializer();
		$id = $serializer->getId($model->getTarget());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getTarget(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'target');
		}

		return null;
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
