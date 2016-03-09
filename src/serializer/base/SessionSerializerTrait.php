<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\User;
use Tobscure\JsonApi\Resource;

/**
 */
trait SessionSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'token' => $model->Token(),
			'user_id' => $model->UserId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['token', 'user_id', 'created_at', 'updated_at'];
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
			'user' => User::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['token', 'user_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/session';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['token', 'user_id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setUser($model, $data) {
		$model->setUserId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function user($model, $related) {
		$serializer = User::getSerializer();
		$relationship = new Relationship(new Resource($model->getUser(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}
}
