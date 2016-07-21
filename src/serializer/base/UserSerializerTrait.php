<?php
namespace keeko\core\serializer\base;

use keeko\core\model\Activity;
use keeko\core\model\Group;
use keeko\core\model\Session;
use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;

/**
 */
trait UserSerializerTrait {

	/**
	 */
	private $methodNames = [
		'sessions' => 'Session',
		'groups' => 'Group',
		'activities' => 'Activity'
	];

	/**
	 */
	private $methodPluralNames = [
		'sessions' => 'Sessions',
		'groups' => 'Groups',
		'activities' => 'Activities'
	];

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function activities($model) {
		$method = 'get' . $this->getCollectionMethodPluralName('activities');
		$relationship = new Relationship(new Collection($model->$method(), Activity::getSerializer()));
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
		$method = 'get' . $this->getCollectionMethodPluralName('groups');
		$relationship = new Relationship(new Collection($model->$method(), Group::getSerializer()));
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

		// hydrate
		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'user-name', 'password', 'given-name', 'family-name', 'nick-name', 'display-name-user-select', 'email', 'birth', 'sex', 'slug']);

		// relationships
		//$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function sessions($model) {
		$method = 'get' . $this->getCollectionMethodPluralName('sessions');
		$relationship = new Relationship(new Collection($model->$method(), Session::getSerializer()));
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
	 * @param mixed $relatedName
	 */
	protected function getCollectionMethodName($relatedName) {
		if (isset($this->methodNames[$relatedName])) {
			return $this->methodNames[$relatedName];
		}
		return null;
	}

	/**
	 * @param mixed $relatedName
	 */
	protected function getCollectionMethodPluralName($relatedName) {
		if (isset($this->methodPluralNames[$relatedName])) {
			return $this->methodPluralNames[$relatedName];
		}
		return null;
	}

	/**
	 */
	protected function getTypeInferencer() {
		return TypeInferencer::getInstance();
	}
}
