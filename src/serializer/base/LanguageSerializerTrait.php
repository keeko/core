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
			'alpha_2' => $model->getAlpha2(),
			'alpha_3T' => $model->getAlpha3T(),
			'alpha_3B' => $model->getAlpha3B(),
			'alpha_3' => $model->getAlpha3(),
			'parent_id' => $model->getParentId(),
			'macrolanguage_status' => $model->getMacrolanguageStatus(),
			'name' => $model->getName(),
			'native_name' => $model->getNativeName(),
			'collate' => $model->getCollate(),
			'subtag' => $model->getSubtag(),
			'prefix' => $model->getPrefix(),
			'scope_id' => $model->getScopeId(),
			'type_id' => $model->getTypeId(),
			'family_id' => $model->getFamilyId(),
			'default_script_id' => $model->getDefaultScriptId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'alpha_2', 'alpha_3T', 'alpha_3B', 'alpha_3', 'parent_id', 'macrolanguage_status', 'name', 'native_name', 'collate', 'subtag', 'prefix', 'scope_id', 'type_id', 'family_id', 'default_script_id'];
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
		return ['id', 'alpha_2', 'alpha_3T', 'alpha_3B', 'alpha_3', 'parent_id', 'macrolanguage_status', 'name', 'native_name', 'collate', 'subtag', 'prefix', 'scope_id', 'type_id', 'family_id', 'default_script_id'];
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
