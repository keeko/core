<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Application;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Localization;

/**
 */
class ApplicationUriSerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function application($model, $related) {
		$serializer = Application::getSerializer();
		$relationship = new Relationship(new Resource($model->getApplication(), $serializer));
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
			'httphost' => $model->Httphost(),
			'basepath' => $model->Basepath(),
			'secure' => $model->Secure(),
			'application_id' => $model->ApplicationId(),
			'localization_id' => $model->LocalizationId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'httphost', 'basepath', 'secure', 'application_id', 'localization_id'];
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
			'application' => Application::getSerializer()->getType(null),
			'localization' => Localization::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'httphost', 'basepath', 'secure', 'application_id', 'localization_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/application-uri';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'httphost', 'basepath', 'secure', 'application_id', 'localization_id']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function localization($model, $related) {
		$serializer = Localization::getSerializer();
		$relationship = new Relationship(new Resource($model->getLocalization(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setApplication($model, $data) {
		$model->setApplicationId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setLocalization($model, $data) {
		$model->setLocalizationId($data['id']);
	}
}
