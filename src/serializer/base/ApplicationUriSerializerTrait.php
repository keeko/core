<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Application;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Localization;

/**
 */
trait ApplicationUriSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function application($model) {
		$serializer = Application::getSerializer();
		$relationship = new Relationship(new Resource($model->getApplication(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'application');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'httphost' => $model->getHttphost(),
			'basepath' => $model->getBasepath(),
			'secure' => $model->getSecure(),
			'application-id' => $model->getApplicationId(),
			'localization-id' => $model->getLocalizationId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'httphost', 'basepath', 'secure', 'application-id', 'localization-id'];
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
			'application' => Application::getSerializer()->getType(null),
			'localization' => Localization::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'httphost', 'basepath', 'secure', 'application-id', 'localization-id'];
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

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'httphost', 'basepath', 'secure', 'application-id', 'localization-id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function localization($model) {
		$serializer = Localization::getSerializer();
		$relationship = new Relationship(new Resource($model->getLocalization(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'localization');
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
