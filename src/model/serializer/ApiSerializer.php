<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Action;
use Tobscure\JsonApi\Resource;

/**
 */
class ApiSerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function action($model, $related) {
		$serializer = Action::getSerializer();
		$relationship = new Relationship(new Resource($model->getAction(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'route' => $model->Route(),
			'method' => $model->Method(),
			'action_id' => $model->ActionId(),
			'required_params' => $model->RequiredParams(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'route', 'method', 'action_id', 'required_params'];
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
	 */
	public function getType($model) {
		return 'core/api';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
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
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setAction($model, $data) {
		$model->setActionId($data['id']);
	}
}
