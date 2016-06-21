<?php
namespace keeko\core\serializer\base;

use keeko\core\model\ApplicationUri;
use keeko\core\model\Language;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageVariant;
use keeko\core\model\Localization;
use keeko\core\serializer\TypeInferencer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;

/**
 */
trait LocalizationSerializerTrait {

	/**
	 */
	private $methodNames = [
		'localizations' => 'Localization',
		'language-variants' => 'LanguageVariant',
		'application-uris' => 'ApplicationUri'
	];

	/**
	 */
	private $methodPluralNames = [
		'localizations' => 'Localizations',
		'language-variants' => 'LanguageVariants',
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
	 * @return Relationship
	 */
	public function extLang($model) {
		$serializer = Language::getSerializer();
		$id = $serializer->getId($model->getExtLang());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getExtLang(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'ext-lang');
		}

		return null;
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'name' => $model->getName(),
			'locale' => $model->getLocale(),
			'region' => $model->getRegion(),
			'is-default' => $model->getIsDefault()
		];
	}

	/**
	 */
	public function getFields() {
		return ['name', 'locale', 'region', 'is-default'];
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
			'localizations' => Localization::getSerializer()->getType(null),
			'parent' => Localization::getSerializer()->getType(null),
			'language' => Language::getSerializer()->getType(null),
			'ext-lang' => Language::getSerializer()->getType(null),
			'script' => LanguageScript::getSerializer()->getType(null),
			'language-variants' => LanguageVariant::getSerializer()->getType(null),
			'application-uris' => ApplicationUri::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['name', 'locale', 'region', 'is-default'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/localization';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// attributes
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'parent-id', 'name', 'locale', 'language-id', 'ext-language-id', 'region', 'script-id', 'is-default']);

		// relationships
		//$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function language($model) {
		$serializer = Language::getSerializer();
		$id = $serializer->getId($model->getLanguage());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getLanguage(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'language');
		}

		return null;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function languageVariants($model) {
		$method = 'get' . $this->getCollectionMethodPluralName('language-variants');
		$relationship = new Relationship(new Collection($model->$method(), LanguageVariant::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'language-variant');
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function localizations($model) {
		$method = 'get' . $this->getCollectionMethodPluralName('localizations');
		$relationship = new Relationship(new Collection($model->$method(), Localization::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'localization');
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function parent($model) {
		$serializer = Localization::getSerializer();
		$id = $serializer->getId($model->getParent());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getParent(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'parent');
		}

		return null;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function script($model) {
		$serializer = LanguageScript::getSerializer();
		$id = $serializer->getId($model->getScript());
		if ($id !== null) {
			$relationship = new Relationship(new Resource($model->getScript(), $serializer));
			$relationship->setLinks([
				'related' => '%apiurl%' . $serializer->getType(null) . '/' . $id 
			]);
			return $this->addRelationshipSelfLink($relationship, $model, 'script');
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
