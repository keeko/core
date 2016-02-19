<?php
namespace keeko\core\model\serializer;

use keeko\core\model\serializer\AbstractSerializer;
use keeko\core\utils\HydrateUtils;
use keeko\core\model\UserQuery;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use keeko\core\model\User;
use Tobscure\JsonApi\Collection;
use keeko\core\model\UserGroupQuery;

class GroupSerializer extends AbstractSerializer {

	public function getType($model) {
		return 'group/groups';
	}
	
	public function getId($model) {
		return $model->getId();
	}
	
	public function getAttributes($model, array $fields = null) {
		return [
			'name' => $model->getName(),
			'is_guest' => $model->getIsGuest(),
			'is_default' => $model->getIsDefault(),
			'is_active' => $model->getIsActive(),
			'is_system' => $model->getIsSystem()
		];
	}
	
	public function getFields() {
		return ['name', 'is_guest', 'is_default', 'is_active', 'is_system'];
	}
	
	public function getSortFields() {
		return ['name'];
	}

	public function users($model, $related) {
		$relationship = new Relationship(new Collection($model->getUsers(), User::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	public function owner($model) {
		$user = UserQuery::create()->findOneById($model->getOwnerId());
		$serializer = User::getSerializer();
		$relationship = new Relationship(new Resource($user, $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType() . '/' . $serializer->getId($user)
		]);
		return $relationship;
	}

	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];
		
		$model = HydrateUtils::hydrate($attribs, $model, ['login_name', 'password' => function ($v) {
			return password_hash($v, PASSWORD_BCRYPT);
		}, 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex']);
		
		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}
	
	public function getRelationships() {
		return [
			'users' => 'user/users',
			'owner' => 'user/users'
		];
	}
	
	public function setUsers($model, $data) {
		UserGroupQuery::create()->filterByGroup($model)->deleteAll();
		$this->addUsers($model, $data);
	}
	
	public function addUsers($model, $data) {
		foreach ($data as $item) {
			$group = UserQuery::create()->findOneById($item['id']);
			if ($group !== null) {
				$model->addUser($group);
			}
		}
	}
	
	public function removeUsers($model, $data) {
		foreach ($data as $item) {
			$group = UserQuery::create()->findOneById($item['id']);
			if ($group !== null) {
				$model->removeUser($group);
			}
		}
	}
	
	public function setOwner($model, $data) {
		$model->setOwnerId($data['id']);
	}
}