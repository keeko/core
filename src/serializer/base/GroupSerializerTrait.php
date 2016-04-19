<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\User;
use Tobscure\JsonApi\Collection;
use keeko\core\model\Action;

/**
 */
trait GroupSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function actions($model) {
		$relationship = new Relationship(new Collection($model->getActions(), Action::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'actions');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'owner_id' => $model->getOwnerId(),
			'name' => $model->getName(),
			'is_guest' => $model->getIsGuest(),
			'is_default' => $model->getIsDefault(),
			'is_active' => $model->getIsActive(),
			'is_system' => $model->getIsSystem(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'owner_id', 'name', 'is_guest', 'is_default', 'is_active', 'is_system', 'created_at', 'updated_at'];
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
			'users' => User::getSerializer()->getType(null),
			'actions' => Action::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'owner_id', 'name', 'is_guest', 'is_default', 'is_active', 'is_system'];
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

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'owner_id', 'name', 'is_guest', 'is_default', 'is_active', 'is_system']);

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
		return $this->addRelationshipSelfLink($relationship, $model, 'users');
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
