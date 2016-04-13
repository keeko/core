<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageScope;
use keeko\core\model\LanguageScopeQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageScopeDomainTrait {

	/**
	 * Returns a paginated result
	 * 
	 * @param Parameters $params
	 */
	public function paginate(Parameters $params) {
		$sysPrefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$defaultSize = $sysPrefs->getPaginationSize();
		$page = $params->getPage('number');
		$size = $params->getPage('size', $defaultSize);

		$query = LanguageScopeQuery::create();

		// sorting
		$sort = $params->getSort(LanguageScope::getSerializer()->getSortFields());
		foreach ($sort as $field => $order) {
			$method = 'orderBy' . NameUtils::toStudlyCase($field);
			$query->$method($order);
		}

		// filtering
		$filter = $params->getFilter();
		if (!empty($filter)) {
			$this->applyFilter($query, $filter);
		}

		// paginate
		$languageScope = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageScope]);
	}

	/**
	 * Returns one LanguageScope with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$languageScope = LanguageScopeQuery::create()->findOneById($id);

		// check existence
		if ($languageScope === null) {
			$payload = new NotFound(['message' => 'LanguageScope not found.']);
		} else {
			$payload = new Found(['model' => $languageScope]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageScopeDomain
	 * 
	 * @param LanguageScopeQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(LanguageScopeQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
