<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Activity;
use Tobscure\JsonApi\Collection;

/**
 */
trait ActivityObjectSerializerTrait {

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
			'id' => $model->getId(),
			'class-name' => $model->getClassName(),
			'type' => $model->getType(),
			'display-name' => $model->getDisplayName(),
			'url' => $model->getUrl(),
			'reference-id' => $model->getReferenceId(),
			'version' => $model->getVersion(),
			'extra' => $model->getExtra(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'class-name', 'type', 'display-name', 'url', 'reference-id', 'version', 'extra'];
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
			'activities' => Activity::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'class-name', 'type', 'display-name', 'url', 'reference-id', 'version', 'extra'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/activity-object';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'class-name', 'type', 'display-name', 'url', 'reference-id', 'version', 'extra']);

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