<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Package;
use Tobscure\JsonApi\Resource;

/**
 */
trait ExtensionSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'key' => $model->getKey(),
			'data' => $model->getData(),
			'package_id' => $model->getPackageId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'key', 'data', 'package_id'];
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
	 * @return string
	 */
	public function getType($model) {
		return 'core/extension';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
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
	 * @return Relationship
	 */
	public function package($model) {
		$serializer = Package::getSerializer();
		$relationship = new Relationship(new Resource($model->getPackage(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'package');
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
