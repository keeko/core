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
			'user_name' => $model->getUserName(),
			'given_name' => $model->getGivenName(),
			'family_name' => $model->getFamilyName(),
			'nick_name' => $model->getNickName(),
			'display_name' => $model->getDisplayName(),
			'email' => $model->getEmail(),
			'birth' => $model->getBirth(\DateTime::ISO8601),
			'sex' => $model->getSex(),
			'slug' => $model->getSlug(),
			'password_recover_token' => $model->getPasswordRecoverToken(),
			'created_at' => $model->getCreatedAt(\DateTime::ISO8601),
			'updated_at' => $model->getUpdatedAt(\DateTime::ISO8601),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'user_name', 'given_name', 'family_name', 'nick_name', 'display_name', 'email', 'birth', 'sex', 'slug', 'password_recover_token', 'created_at', 'updated_at'];
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
		return ['id', 'user_name', 'given_name', 'family_name', 'nick_name', 'display_name', 'email', 'birth', 'sex', 'slug', 'password_recover_token', 'created_at', 'updated_at'];
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

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'user_name', 'password' => function($v) {
			return password_hash($v, PASSWORD_BCRYPT);
		}, 'given_name', 'family_name', 'nick_name', 'display_name', 'email', 'birth', 'sex', 'slug', 'password_recover_token']);

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
