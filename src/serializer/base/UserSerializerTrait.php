<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Group;
use Tobscure\JsonApi\Collection;

/**
 */
trait UserSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'login_name' => $model->getLoginName(),
			'password' => $model->getPassword(),
			'given_name' => $model->getGivenName(),
			'family_name' => $model->getFamilyName(),
			'display_name' => $model->getDisplayName(),
			'email' => $model->getEmail(),
			'birthday' => $model->getBirthday(\DateTime::ISO8601),
			'sex' => $model->getSex(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'login_name', 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex', 'created_at', 'updated_at'];
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
			'groups' => Group::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'login_name', 'password', 'given_name', 'family_name', 'display_name', 'email', 'birthday', 'sex'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/user';
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function groups($model) {
		$relationship = new Relationship(new Collection($model->getGroups(), Group::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'groups');
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
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
