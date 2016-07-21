<?php
namespace keeko\core\serializer\base;

use keeko\core\model\Application;
use keeko\core\model\Localization;
use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

/**
 */
trait ApplicationUriSerializerTrait {

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
	public function application($model) {
		$serializer = Application::getSerializer();
		$id = $serializer->getId($model->getApplication());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getApplication(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'application');
		}

		return null;
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'httphost' => $model->getHttphost(),
			'basepath' => $model->getBasepath(),
			'secure' => $model->getSecure()
		];
	}

	/**
	 */
	public function getFields() {
		return ['httphost', 'basepath', 'secure'];
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
			'application' => Application::getSerializer()->getType(null),
			'localization' => Localization::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['httphost', 'basepath', 'secure'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/application-uri';
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
		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'httphost', 'basepath', 'secure', 'application-id', 'localization-id']);

		// relationships
		//$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function localization($model) {
		$serializer = Localization::getSerializer();
		$id = $serializer->getId($model->getLocalization());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getLocalization(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'localization');
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
