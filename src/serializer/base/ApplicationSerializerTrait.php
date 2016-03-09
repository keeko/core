<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Package;
use Tobscure\JsonApi\Resource;

/**
 */
trait ApplicationSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'class_name' => $model->ClassName(),
			'id' => $model->Id(),
			'name' => $model->Name(),
			'title' => $model->Title(),
			'description' => $model->Description(),
			'installed_version' => $model->InstalledVersion(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['class_name', 'id', 'name', 'title', 'description', 'installed_version'];
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
			'package' => Package::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['class_name', 'id', 'name', 'title', 'description', 'installed_version'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/application';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['class_name', 'id', 'name', 'title', 'description', 'installed_version']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function package($model, $related) {
		$serializer = Package::getSerializer();
		$relationship = new Relationship(new Resource($model->getPackage(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setPackage($model, $data) {
		$model->setPackageId($data['id']);
	}
}
