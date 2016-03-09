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
			'id' => $model->Id(),
			'alpha_2' => $model->Alpha2(),
			'alpha_3T' => $model->Alpha3T(),
			'alpha_3B' => $model->Alpha3B(),
			'alpha_3' => $model->Alpha3(),
			'parent_id' => $model->ParentId(),
			'macrolanguage_status' => $model->MacrolanguageStatus(),
			'name' => $model->Name(),
			'native_name' => $model->NativeName(),
			'collate' => $model->Collate(),
			'subtag' => $model->Subtag(),
			'prefix' => $model->Prefix(),
			'scope_id' => $model->ScopeId(),
			'type_id' => $model->TypeId(),
			'family_id' => $model->FamilyId(),
			'default_script_id' => $model->DefaultScriptId(),
		];
	}

	/**
	 */
	public function getFields() {
		return ['id', 'alpha_2', 'alpha_3T', 'alpha_3B', 'alpha_3', 'parent_id', 'macrolanguage_status', 'name', 'native_name', 'collate', 'subtag', 'prefix', 'scope_id', 'type_id', 'family_id', 'default_script_id'];
	}

	/**
	 * @param mixed $model
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
	 */
	public function getType($model) {
		return 'core/language';
	}

	/**
	 * @param mixed $model
	 * @param mixed $data
	 */
	public function hydrate($model, $data) {
		// this model is read-only!
		return $model;
	}
}
