<?php
namespace keeko\core\serializer\base;

use keeko\core\model\Action;
use keeko\core\model\User;
use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;

/**
 */
trait GroupSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function actions($model) {
		$relationship = new Relationship(new Collection($model->getActions(), Action::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'action');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'owner-id' => $model->getOwnerId(),
			'name' => $model->getName(),
			'is-guest' => $model->getIsGuest(),
			'is-default' => $model->getIsDefault(),
			'is-active' => $model->getIsActive(),
			'is-system' => $model->getIsSystem(),
			'created-at' => $model->getCreatedAt(\DateTime::ISO8601),
			'updated-at' => $model->getUpdatedAt(\DateTime::ISO8601)
		];
	}

	/**
	 */
	public function getFields() {
		return ['owner-id', 'name', 'is-guest', 'is-default', 'is-active', 'is-system', 'created-at', 'updated-at'];
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
			'users' => User::getSerializer()->getType(null),
			'actions' => Action::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['owner-id', 'name', 'is-guest', 'is-default', 'is-active', 'is-system', 'created-at', 'updated-at'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/group';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'owner-id', 'name', 'is-guest', 'is-default', 'is-active', 'is-system']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function users($model) {
		$relationship = new Relationship(new Collection($model->getUsers(), User::getSerializer()));
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
	 */
	protected function getTypeInferencer() {
		return TypeInferencer::getInstance();
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return void
	 */
	abstract protected function hydrateRelationships($model, $data);
}
