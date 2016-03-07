<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\User;
use keeko\core\model\UserQuery;
use Tobscure\JsonApi\Collection;
use keeko\core\model\UserGroupQuery;

/**
 */
class GroupSerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function addUsers($model, $data) {
		foreach ($data as $item) {
			$user = UserQuery::create()->findOneById($item['id']);
			if ($user !== null) {
				$model->addUser($user);
			}
		}
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'owner_id' => $model->OwnerId(),
			'name' => $model->Name(),
			'is_guest' => $model->IsGuest(),
			'is_default' => $model->IsDefault(),
			'is_active' => $model->IsActive(),
			'is_system' => $model->IsSystem(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'owner_id', 'name', 'is_guest', 'is_default', 'is_active', 'is_system', 'created_at', 'updated_at'];
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
		return ['id', 'owner_id', 'name', 'is_guest', 'is_default', 'is_active', 'is_system'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/group';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
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
	 * @param mixed $data
	 */
	public function removeUsers($model, $data) {
		foreach ($data as $item) {
			$user = UserQuery::create()->findOneById($item['id']);
			if ($user !== null) {
				$model->removeUser($user);
			}
		}
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setUsers($model, $data) {
		UserGroupQuery::create()->filterByUser($model)->delete();
		$this->addUsers($model, $data);
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function user($model, $related) {
		$relationship = new Relationship(new Collection($model->getUsers(), User::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}
}
