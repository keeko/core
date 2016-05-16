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
			'token' => $model->getToken(),
			'user-id' => $model->getUserId(),
			'ip' => $model->getIp(),
			'user-agent' => $model->getUserAgent(),
			'browser' => $model->getBrowser(),
			'device' => $model->getDevice(),
			'os' => $model->getOs(),
			'location' => $model->getLocation(),
			'created-at' => $model->getCreatedAt(\DateTime::ISO8601),
			'updated-at' => $model->getUpdatedAt(\DateTime::ISO8601),
		];
	}

	/**
	 */
	public function getFields() {
		return ['token', 'user-id', 'ip', 'user-agent', 'browser', 'device', 'os', 'location', 'created-at', 'updated-at'];
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
			'user' => User::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['token', 'user-id', 'ip', 'user-agent', 'browser', 'device', 'os', 'location', 'created-at', 'updated-at'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/session';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['token', 'user-id', 'ip', 'user-agent', 'browser', 'device', 'os', 'location']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function user($model) {
		$serializer = User::getSerializer();
		$relationship = new Relationship(new Resource($model->getUser(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'user');
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
