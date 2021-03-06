<?php
namespace keeko\core\serializer\base;

use keeko\core\model\Api;
use keeko\core\model\Group;
use keeko\core\model\Module;
use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

/**
 */
trait ActionSerializerTrait {

	/**
	 */
	private $methodNames = [
		'groups' => 'Group',
		'apis' => 'Api'
	];

	/**
	 */
	private $methodPluralNames = [
		'groups' => 'Groups',
		'apis' => 'Apis'
	];

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function apis($model) {
		$method = 'get' . $this->getCollectionMethodPluralName('apis');
		$relationship = new Relationship(new Collection($model->$method(), Api::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'api');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'name' => $model->getName(),
			'title' => $model->getTitle(),
			'description' => $model->getDescription(),
			'class-name' => $model->getClassName()
		];
	}

	/**
	 */
	public function getFields() {
		return ['name', 'title', 'description', 'class-name'];
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
			'module' => Module::getSerializer()->getType(null),
			'groups' => Group::getSerializer()->getType(null),
			'apis' => Api::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['name', 'title', 'description', 'class-name'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/action';
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
		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'name', 'title', 'description', 'class-name', 'module-id']);

		// relationships
		//$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function module($model) {
		$serializer = Module::getSerializer();
		$id = $serializer->getId($model->getModule());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getModule(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'module');
		}

		return null;
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
