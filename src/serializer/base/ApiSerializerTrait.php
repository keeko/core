<?php
namespace keeko\core\serializer\base;

use keeko\core\model\Action;
use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

/**
 */
trait ApiSerializerTrait {

	/**
	 */
	private $methodNames = [

	];

	/**
	 */
	private $methodPluralNames = [

	];

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function action($model) {
		$serializer = Action::getSerializer();
		$id = $serializer->getId($model->getAction());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getAction(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'action');
		}

		return null;
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'route' => $model->getRoute(),
			'method' => $model->getMethod(),
			'required-params' => $model->getRequiredParams()
		];
	}

	/**
	 */
	public function getFields() {
		return ['route', 'method', 'required-params'];
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
			'action' => Action::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['route', 'method', 'required-params'];
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

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'route', 'method', 'action-id', 'required-params']);

		// relationships
		//$this->hydrateRelationships($model, $data);

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
