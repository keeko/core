<?php
namespace keeko\core\model\serializer;

use keeko\framework\model\AbstractSerializer;
use keeko\framework\utils\HydrateUtils;
use Tobscure\JsonApi\Relationship;
use keeko\core\model\Localization;
use Tobscure\JsonApi\Resource;
use keeko\core\model\Language;
use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageVariant;
use keeko\core\model\LanguageVariantQuery;
use Tobscure\JsonApi\Collection;
use keeko\core\model\LocalizationVariantQuery;

/**
 */
class LocalizationSerializer extends AbstractSerializer {

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function addLanguageVariants($model, $data) {
		foreach ($data as $item) {
			$languageVariant = LanguageVariantQuery::create()->findOneById($item['id']);
			if ($languageVariant !== null) {
				$model->addLanguageVariant($languageVariant);
			}
		}
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function extLang($model, $related) {
		$serializer = Language::getSerializer();
		$relationship = new Relationship(new Resource($model->getExtLang(), $serializer));
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
			'localization' => Localization::getSerializer()->getType(null),
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
	 * @param mixed $related
	 */
	public function language($model, $related) {
		$serializer = Language::getSerializer();
		$relationship = new Relationship(new Resource($model->getLanguage(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function languageVariant($model, $related) {
		$relationship = new Relationship(new Collection($model->getLanguageVariants(), LanguageVariant::getSerializer()));
		return $this->addRelationshipSelfLink($relationship, $model, $related);
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
	public function removeLanguageVariants($model, $data) {
		foreach ($data as $item) {
			$languageVariant = LanguageVariantQuery::create()->findOneById($item['id']);
			if ($languageVariant !== null) {
				$model->removeLanguageVariant($languageVariant);
			}
		}
	}

	/**
	 * @param mixed $model
	 * @param mixed $related
	 */
	public function script($model, $related) {
		$serializer = LanguageScript::getSerializer();
		$relationship = new Relationship(new Resource($model->getScript(), $serializer));
		$relationship->setLinks([
			'related' => '%apiurl%' . $serializer->getType(null) . '/' . $serializer->getId($model)
		]);
		return $this->addRelationshipSelfLink($relationship, $model, $related);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setExtLang($model, $data) {
		$model->setExtLangId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setLanguage($model, $data) {
		$model->setLanguageId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setLanguageVariants($model, $data) {
		LocalizationVariantQuery::create()->filterByLanguageVariant($model)->delete();
		$this->addLanguageVariants($model, $data);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setLocalization($model, $data) {
		$model->setLocalizationId($data['id']);
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function setScript($model, $data) {
		$model->setScriptId($data['id']);
	}
}
