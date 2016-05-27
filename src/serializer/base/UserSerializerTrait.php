<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Session;
use Tobscure\JsonApi\Collection;
use keeko\core\model\Group;
use keeko\core\model\Activity;

/**
 */
trait UserSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function activities($model) {
		$relationship = new Relationship(new Collection($model->getActivities(), Activity::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'activity');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'user-name' => $model->getUserName(),
			'given-name' => $model->getGivenName(),
			'family-name' => $model->getFamilyName(),
			'nick-name' => $model->getNickName(),
			'display-name' => $model->getDisplayName(),
			'email' => $model->getEmail(),
			'birth' => $model->getBirth(\DateTime::ISO8601),
			'sex' => $model->getSex(),
			'slug' => $model->getSlug(),
			'created-at' => $model->getCreatedAt(\DateTime::ISO8601),
			'updated-at' => $model->getUpdatedAt(\DateTime::ISO8601)
		];
	}

	/**
	 */
	public function getFields() {
		return ['user-name', 'given-name', 'family-name', 'nick-name', 'display-name', 'email', 'birth', 'sex', 'slug', 'created-at', 'updated-at'];
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
			'sessions' => Session::getSerializer()->getType(null),
			'groups' => Group::getSerializer()->getType(null),
			'activities' => Activity::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['user-name', 'given-name', 'family-name', 'nick-name', 'display-name', 'email', 'birth', 'sex', 'slug', 'created-at', 'updated-at'];
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
		return $this->addRelationshipSelfLink($relationship, $model, 'group');
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$service = $this->getServiceContainer();
		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'user-name', 'password' => function ($value) use ($service) {
			if (class_exists('keeko\core\normalizer\PasswordNormalizer')) {
				$normalizer = new keeko\core\normalizer\PasswordNormalizer();
				$normalizer->setServiceContainer($service);
				return $normalizer->normalize($value);
			}
			return $value;
		}, 'given-name', 'family-name', 'nick-name', 'display-name-user-select', 'email', 'birth', 'sex', 'slug']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function sessions($model) {
		$relationship = new Relationship(new Collection($model->getSessions(), Session::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'session');
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
