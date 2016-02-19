<?php
namespace keeko\core\model\serializer;

use keeko\core\model\serializer\AbstractSerializer;
use keeko\core\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Group;
use Tobscure\JsonApi\Collection;
use keeko\core\model\UserGroupQuery;
use keeko\core\model\GroupQuery;

class UserSerializer extends AbstractSerializer {

	public function getType($model) {
		return 'user/users';
	}
	
	public function getId($model) {
		return $model->getId();
	}
	
	public function getAttributes($model, array $fields = null) {
		return [
			'login_name' => $model->getLoginName(),
			'given_name' => $model->getGivenName(),
			'family_name' => $model->getFamilyName(),
			'display_name' => $model->getDisplayName(),
			'email' => $model->getEmail(),
			'birthday' => $model->getBirthday(\DateTime::ISO8601),
			'sex' => $model->getSex(),
			'created_at' => $model->getCreatedAt(\DateTime::ISO8601),
			'updated_at' => $model->getUpdatedAt(\DateTime::ISO8601)
		];
	}
	
	public function getFields() {
		return ['login_name', 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex', 'created_at', 'updated_at'];
	}
	
	public function getSortFields() {
		return ['login_name', 'given_name', 'family_name', 'display_name', 'email', 'birthday'];
	}
	
	public function groups($model, $related) {
		$relationship = new Relationship(new Collection($model->getGroups(), Group::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}
	
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];
		
		$user = HydrateUtils::hydrate($attribs, $model, ['login_name', 'password' => function ($v) {
			return password_hash($v, PASSWORD_BCRYPT);
		}, 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $user;
	}
	
	public function getRelationships() {
		return ['group' => 'group/groups'];
	}

	public function setGroups($model, $data) {
		UserGroupQuery::create()->filterByUser($model)->deleteAll();
		$this->addGroups($model, $data);
	}
	
	public function addGroups($model, $data) {
		foreach ($data as $item) {
			$group = GroupQuery::create()->findOneById($item['id']);
			if ($group !== null) {
				$model->addGroup($group);
			}
		}
	}
	
	public function removeGroups($model, $data) {
		foreach ($data as $item) {
			$group = GroupQuery::create()->findOneById($item['id']);
			if ($group !== null) {
				$model->removeGroup($group);
			}
		}
	}
}