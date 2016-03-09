<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Group;
use keeko\core\model\GroupQuery;
use Tobscure\JsonApi\Collection;
use keeko\core\model\UserGroupQuery;

/**
 */
trait UserSerializerTrait {

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function addGroups($model, $data) {
		foreach ($data as $item) {
			$group = GroupQuery::create()->findOneById($item['id']);
			if ($group !== null) {
				$model->addGroup($group);
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
			'login_name' => $model->LoginName(),
			'password' => $model->Password(),
			'given_name' => $model->GivenName(),
			'family_name' => $model->FamilyName(),
			'display_name' => $model->DisplayName(),
			'email' => $model->Email(),
			'birthday' => $model->Birthday(\DateTime::ISO8601),
			'sex' => $model->Sex(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'login_name', 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex', 'created_at', 'updated_at'];
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
			'group' => Group::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'login_name', 'password', 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/user';
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function group($model, $related) {
		$relationship = new Relationship(new Collection($model->getGroups(), Group::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'login_name', 'password' => function($v) {
			return password_hash($v, PASSWORD_BCRYPT);
		}, 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function removeGroups($model, $data) {
		foreach ($data as $item) {
			$group = GroupQuery::create()->findOneById($item['id']);
			if ($group !== null) {
				$model->removeGroup($group);
			}
		}
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setGroups($model, $data) {
		UserGroupQuery::create()->filterByGroup($model)->delete();
		$this->addGroups($model, $data);
	}
}
