<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Action;
use Tobscure\JsonApi\Resource;

/**
 */
trait ApiSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function action($model) {
		$serializer = Action::getSerializer();
		$relationship = new Relationship(new Resource($model->getAction(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'action');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'route' => $model->getRoute(),
			'method' => $model->getMethod(),
			'action_id' => $model->getActionId(),
			'required_params' => $model->getRequiredParams(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'route', 'method', 'action_id', 'required_params'];
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
			'action' => Action::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'route', 'method', 'action_id', 'required_params'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/api';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'route', 'method', 'action_id', 'required_params']);

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
