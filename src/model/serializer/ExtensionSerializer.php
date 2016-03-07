<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Package;
use Tobscure\JsonApi\Resource;

/**
 */
class ExtensionSerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->Id(),
			'key' => $model->Key(),
			'data' => $model->Data(),
			'package_id' => $model->PackageId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'key', 'data', 'package_id'];
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
		return ['id', 'key', 'data', 'package_id'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/extension';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'key', 'data', 'package_id']);

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
