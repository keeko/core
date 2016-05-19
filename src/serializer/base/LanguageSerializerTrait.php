<?php
namespace keeko\core\serializer\base;

/**
 */
trait LanguageSerializerTrait {

	/**
	 * @param mixed $model
	 * @param array $fields
	 */
	public function getAttributes($model, array $fields = null) {
		return [
			'id' => $model->getId(),
			'alpha-2' => $model->getAlpha2(),
			'alpha-3-t' => $model->getAlpha3T(),
			'alpha-3-b' => $model->getAlpha3B(),
			'alpha-3' => $model->getAlpha3(),
			'parent-id' => $model->getParentId(),
			'macrolanguage-status' => $model->getMacrolanguageStatus(),
			'name' => $model->getName(),
			'native-name' => $model->getNativeName(),
			'collate' => $model->getCollate(),
			'subtag' => $model->getSubtag(),
			'prefix' => $model->getPrefix(),
			'scope-id' => $model->getScopeId(),
			'type-id' => $model->getTypeId(),
			'family-id' => $model->getFamilyId(),
			'default-script-id' => $model->getDefaultScriptId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'alpha-2', 'alpha-3-t', 'alpha-3-b', 'alpha-3', 'parent-id', 'macrolanguage-status', 'name', 'native-name', 'collate', 'subtag', 'prefix', 'scope-id', 'type-id', 'family-id', 'default-script-id'];
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
	public function getSortFields() {
		return ['id', 'alpha-2', 'alpha-3-t', 'alpha-3-b', 'alpha-3', 'parent-id', 'macrolanguage-status', 'name', 'native-name', 'collate', 'subtag', 'prefix', 'scope-id', 'type-id', 'family-id', 'default-script-id'];
	}

	/**
	 * @param mixed $model
	 * @return string
	 */
	public function getType($model) {
		return 'core/language';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 * @return mixed The model
	 */
	public function hydrate($model, $data) {
		// this model is read-only!
		return $model;
	}
}
