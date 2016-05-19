<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Action;
use Tobscure\JsonApi\Collection;

/**
 */
trait ModuleSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function actions($model) {
		$relationship = new Relationship(new Collection($model->getActions(), Action::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'action');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'class-name' => $model->getClassName(),
			'activated-version' => $model->getActivatedVersion(),
			'default-action' => $model->getDefaultAction(),
			'slug' => $model->getSlug(),
			'has-api' => $model->getApi(),
			'id' => $model->getId(),
			'name' => $model->getName(),
			'title' => $model->getTitle(),
			'description' => $model->getDescription(),
			'installed-version' => $model->getInstalledVersion(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['class-name', 'activated-version', 'default-action', 'slug', 'has-api', 'id', 'name', 'title', 'description', 'installed-version'];
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
			'actions' => Action::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['class-name', 'activated-version', 'default-action', 'slug', 'has-api', 'id', 'name', 'title', 'description', 'installed-version'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/module';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['class-name', 'activated-version', 'default-action', 'slug', 'has-api', 'id', 'name', 'title', 'description', 'installed-version']);

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
