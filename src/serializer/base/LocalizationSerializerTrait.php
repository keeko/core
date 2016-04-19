<?php
namespace keeko\core\serializer\base;

use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Localization;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Language;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageVariant;
use Tobscure\JsonApi\Collection;

/**
 */
trait LocalizationSerializerTrait {

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function extLang($model) {
		$serializer = Language::getSerializer();
		$relationship = new Relationship(new Resource($model->getExtLang(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'ext-lang');
	}

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'parent_id' => $model->getParentId(),
			'name' => $model->getName(),
			'locale' => $model->getLocale(),
			'language_id' => $model->getLanguageId(),
			'ext_language_id' => $model->getExtLanguageId(),
			'region' => $model->getRegion(),
			'script_id' => $model->getScriptId(),
			'is_default' => $model->getIsDefault(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'parent_id', 'name', 'locale', 'language_id', 'ext_language_id', 'region', 'script_id', 'is_default'];
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
			'parent' => Localization::getSerializer()->getType(null),
			'ext-lang' => Language::getSerializer()->getType(null),
			'script' => LanguageScript::getSerializer()->getType(null),
			'language-variants' => LanguageVariant::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'parent_id', 'name', 'locale', 'language_id', 'ext_language_id', 'region', 'script_id', 'is_default'];
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

		$model = HydrateUtils::hydrate($attribs, $model, ['id', 'parent_id', 'name', 'locale', 'language_id', 'ext_language_id', 'region', 'script_id', 'is_default']);

		// relationships
		$this->hydrateRelationships($model, $data);

		return $model;
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function languageVariants($model) {
		$relationship = new Relationship(new Collection($model->getLanguageVariants(), LanguageVariant::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'language-variants');
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function parent($model) {
		$serializer = Localization::getSerializer();
		$relationship = new Relationship(new Resource($model->getParent(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'parent');
	}

	/**
	 * @param mixed $model
	 * @return Relationship
	 */
	public function script($model) {
		$serializer = LanguageScript::getSerializer();
		$relationship = new Relationship(new Resource($model->getScript(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'script');
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	abstract protected function hydrateRelationships($model, $data);
}
