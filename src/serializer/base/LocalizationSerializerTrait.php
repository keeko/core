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
			'id' => $model->Id(),
			'parent_id' => $model->ParentId(),
			'name' => $model->Name(),
			'locale' => $model->Locale(),
			'language_id' => $model->LanguageId(),
			'ext_language_id' => $model->ExtLanguageId(),
			'region' => $model->Region(),
			'script_id' => $model->ScriptId(),
			'is_default' => $model->IsDefault(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'parent_id', 'name', 'locale', 'language_id', 'ext_language_id', 'region', 'script_id', 'is_default'];
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
			'parent' => Localization::getSerializer()->getType(null),
			'language' => Language::getSerializer()->getType(null),
			'ext-lang' => Language::getSerializer()->getType(null),
			'script' => LanguageScript::getSerializer()->getType(null),
			'language-variant' => LanguageVariant::getSerializer()->getType(null)
		];
	}

	/**
	 */
	public function getSortFields() {
		return ['id', 'parent_id', 'name', 'locale', 'language_id', 'ext_language_id', 'region', 'script_id', 'is_default'];
	}

	/**
	 * @param mixed $model
	 */
	public function getType($model) {
		return 'core/localization';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
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
	 */
	public function language($model) {
		$serializer = Language::getSerializer();
		$relationship = new Relationship(new Resource($model->getLanguage(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'language');
	}

	/**
	 * @param mixed $model
	 */
	public function languageVariant($model) {
		$relationship = new Relationship(new Collection($model->getLanguageVariants(), LanguageVariant::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, 'language-variant');
	}

	/**
	 * @param mixed $model
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
	 */
	public function script($model) {
		$serializer = LanguageScript::getSerializer();
		$relationship = new Relationship(new Resource($model->getScript(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, 'script');
	}
}
