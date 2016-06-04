<?php
namespace keeko\core\serializer;

use keeko\framework\model\TypeInferencerInterface;

/**
 */
class TypeInferencer implements TypeInferencerInterface {

	/**
	 */
	private static $instance;

	/**
	 */
	private $types = [
		'core/language' => [
			'modelClass' => 'keeko\core\model\Language',
			'queryClass' => 'keeko\core\model\LanguageQuery'
		],
		'core/languages' => [
			'modelClass' => 'keeko\core\model\Language',
			'queryClass' => 'keeko\core\model\LanguageQuery'
		],
		'core/language-scope' => [
			'modelClass' => 'keeko\core\model\LanguageScope',
			'queryClass' => 'keeko\core\model\LanguageScopeQuery'
		],
		'core/language-scopes' => [
			'modelClass' => 'keeko\core\model\LanguageScope',
			'queryClass' => 'keeko\core\model\LanguageScopeQuery'
		],
		'core/language-type' => [
			'modelClass' => 'keeko\core\model\LanguageType',
			'queryClass' => 'keeko\core\model\LanguageTypeQuery'
		],
		'core/language-types' => [
			'modelClass' => 'keeko\core\model\LanguageType',
			'queryClass' => 'keeko\core\model\LanguageTypeQuery'
		],
		'core/language-script' => [
			'modelClass' => 'keeko\core\model\LanguageScript',
			'queryClass' => 'keeko\core\model\LanguageScriptQuery'
		],
		'core/language-scripts' => [
			'modelClass' => 'keeko\core\model\LanguageScript',
			'queryClass' => 'keeko\core\model\LanguageScriptQuery'
		],
		'core/language-family' => [
			'modelClass' => 'keeko\core\model\LanguageFamily',
			'queryClass' => 'keeko\core\model\LanguageFamilyQuery'
		],
		'core/language-families' => [
			'modelClass' => 'keeko\core\model\LanguageFamily',
			'queryClass' => 'keeko\core\model\LanguageFamilyQuery'
		],
		'core/language-variant' => [
			'modelClass' => 'keeko\core\model\LanguageVariant',
			'queryClass' => 'keeko\core\model\LanguageVariantQuery'
		],
		'core/language-variants' => [
			'modelClass' => 'keeko\core\model\LanguageVariant',
			'queryClass' => 'keeko\core\model\LanguageVariantQuery'
		],
		'core/currency' => [
			'modelClass' => 'keeko\core\model\Currency',
			'queryClass' => 'keeko\core\model\CurrencyQuery'
		],
		'core/currencies' => [
			'modelClass' => 'keeko\core\model\Currency',
			'queryClass' => 'keeko\core\model\CurrencyQuery'
		],
		'core/country' => [
			'modelClass' => 'keeko\core\model\Country',
			'queryClass' => 'keeko\core\model\CountryQuery'
		],
		'core/countries' => [
			'modelClass' => 'keeko\core\model\Country',
			'queryClass' => 'keeko\core\model\CountryQuery'
		],
		'core/continent' => [
			'modelClass' => 'keeko\core\model\Continent',
			'queryClass' => 'keeko\core\model\ContinentQuery'
		],
		'core/continents' => [
			'modelClass' => 'keeko\core\model\Continent',
			'queryClass' => 'keeko\core\model\ContinentQuery'
		],
		'core/subdivision' => [
			'modelClass' => 'keeko\core\model\Subdivision',
			'queryClass' => 'keeko\core\model\SubdivisionQuery'
		],
		'core/subdivisions' => [
			'modelClass' => 'keeko\core\model\Subdivision',
			'queryClass' => 'keeko\core\model\SubdivisionQuery'
		],
		'core/region-type' => [
			'modelClass' => 'keeko\core\model\RegionType',
			'queryClass' => 'keeko\core\model\RegionTypeQuery'
		],
		'core/region-types' => [
			'modelClass' => 'keeko\core\model\RegionType',
			'queryClass' => 'keeko\core\model\RegionTypeQuery'
		],
		'core/region-area' => [
			'modelClass' => 'keeko\core\model\RegionArea',
			'queryClass' => 'keeko\core\model\RegionAreaQuery'
		],
		'core/region-areas' => [
			'modelClass' => 'keeko\core\model\RegionArea',
			'queryClass' => 'keeko\core\model\RegionAreaQuery'
		],
		'core/localization' => [
			'modelClass' => 'keeko\core\model\Localization',
			'queryClass' => 'keeko\core\model\LocalizationQuery'
		],
		'core/localizations' => [
			'modelClass' => 'keeko\core\model\Localization',
			'queryClass' => 'keeko\core\model\LocalizationQuery'
		],
		'core/application' => [
			'modelClass' => 'keeko\core\model\Application',
			'queryClass' => 'keeko\core\model\ApplicationQuery'
		],
		'core/applications' => [
			'modelClass' => 'keeko\core\model\Application',
			'queryClass' => 'keeko\core\model\ApplicationQuery'
		],
		'core/application-uri' => [
			'modelClass' => 'keeko\core\model\ApplicationUri',
			'queryClass' => 'keeko\core\model\ApplicationUriQuery'
		],
		'core/application-uris' => [
			'modelClass' => 'keeko\core\model\ApplicationUri',
			'queryClass' => 'keeko\core\model\ApplicationUriQuery'
		],
		'core/module' => [
			'modelClass' => 'keeko\core\model\Module',
			'queryClass' => 'keeko\core\model\ModuleQuery'
		],
		'core/modules' => [
			'modelClass' => 'keeko\core\model\Module',
			'queryClass' => 'keeko\core\model\ModuleQuery'
		],
		'core/action' => [
			'modelClass' => 'keeko\core\model\Action',
			'queryClass' => 'keeko\core\model\ActionQuery'
		],
		'core/actions' => [
			'modelClass' => 'keeko\core\model\Action',
			'queryClass' => 'keeko\core\model\ActionQuery'
		],
		'core/extension' => [
			'modelClass' => 'keeko\core\model\Extension',
			'queryClass' => 'keeko\core\model\ExtensionQuery'
		],
		'core/extensions' => [
			'modelClass' => 'keeko\core\model\Extension',
			'queryClass' => 'keeko\core\model\ExtensionQuery'
		],
		'core/preference' => [
			'modelClass' => 'keeko\core\model\Preference',
			'queryClass' => 'keeko\core\model\PreferenceQuery'
		],
		'core/preferences' => [
			'modelClass' => 'keeko\core\model\Preference',
			'queryClass' => 'keeko\core\model\PreferenceQuery'
		],
		'core/api' => [
			'modelClass' => 'keeko\core\model\Api',
			'queryClass' => 'keeko\core\model\ApiQuery'
		],
		'core/apis' => [
			'modelClass' => 'keeko\core\model\Api',
			'queryClass' => 'keeko\core\model\ApiQuery'
		],
		'core/session' => [
			'modelClass' => 'keeko\core\model\Session',
			'queryClass' => 'keeko\core\model\SessionQuery'
		],
		'core/sessions' => [
			'modelClass' => 'keeko\core\model\Session',
			'queryClass' => 'keeko\core\model\SessionQuery'
		],
		'core/user' => [
			'modelClass' => 'keeko\core\model\User',
			'queryClass' => 'keeko\core\model\UserQuery'
		],
		'core/users' => [
			'modelClass' => 'keeko\core\model\User',
			'queryClass' => 'keeko\core\model\UserQuery'
		],
		'core/group' => [
			'modelClass' => 'keeko\core\model\Group',
			'queryClass' => 'keeko\core\model\GroupQuery'
		],
		'core/groups' => [
			'modelClass' => 'keeko\core\model\Group',
			'queryClass' => 'keeko\core\model\GroupQuery'
		],
		'core/activity' => [
			'modelClass' => 'keeko\core\model\Activity',
			'queryClass' => 'keeko\core\model\ActivityQuery'
		],
		'core/activities' => [
			'modelClass' => 'keeko\core\model\Activity',
			'queryClass' => 'keeko\core\model\ActivityQuery'
		],
		'core/activity-object' => [
			'modelClass' => 'keeko\core\model\ActivityObject',
			'queryClass' => 'keeko\core\model\ActivityObjectQuery'
		],
		'core/activity-objects' => [
			'modelClass' => 'keeko\core\model\ActivityObject',
			'queryClass' => 'keeko\core\model\ActivityObjectQuery'
		]
	];

	/**
	 */
	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param mixed $type
	 */
	public function getModelClass($type) {
		if (isset($this->types[$type]) && isset($this->types[$type]['modelClass'])) {
			return $this->types[$type]['modelClass'];
		}
	}

	/**
	 * @param mixed $type
	 */
	public function getQueryClass($type) {
		if (isset($this->types[$type]) && isset($this->types[$type]['queryClass'])) {
			return $this->types[$type]['queryClass'];
		}
	}

	/**
	 */
	private function __construct() {
	}
}
