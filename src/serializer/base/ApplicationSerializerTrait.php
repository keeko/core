<?php
namespace keeko\core\serializer\base;

use keeko\core\model\ApplicationUri;
use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;

/**
 */
trait ApplicationSerializerTrait {

	/**
	 */
	private $methodNames = [
		'application-uris' => 'ApplicationUri'
	];

	/**
	 */
	private $methodPluralNames = [
		'application-uris' => 'ApplicationUris'
	];

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function applicationUris($model) {
		$method = 'get' . $this->getCollectionMethodPluralName('application-uris');
		$relationship = new Relationship(new Collection($model->$method(), ApplicationUri::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'application-uri');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'class-name' => $model->getClassName(),
			'name' => $model->getName(),
			'title' => $model->getTitle(),
			'description' => $model->getDescription(),
			'installed-version' => $model->getInstalledVersion()
		];
	}

	/**
	 */
	public function getFields() {
		return ['class-name', 'name', 'title', 'description', 'installed-version'];
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
			'application-uris' => ApplicationUri::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['class-name', 'name', 'title', 'description', 'installed-version'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/application';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['class-name', 'id', 'name', 'title', 'description', 'installed-version']);

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
