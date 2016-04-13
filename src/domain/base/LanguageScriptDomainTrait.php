<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageScriptQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageScriptDomainTrait {

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

		$query = LanguageScriptQuery::create();

		// sorting
		$sort = $params->getSort(LanguageScript::getSerializer()->getSortFields());
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
		$languageScript = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageScript]);
	}

	/**
	 * Returns one LanguageScript with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$languageScript = LanguageScriptQuery::create()->findOneById($id);

		// check existence
		if ($languageScript === null) {
			$payload = new NotFound(['message' => 'LanguageScript not found.']);
		} else {
			$payload = new Found(['model' => $languageScript]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageScriptDomain
	 * 
	 * @param LanguageScriptQuery $query
	 */
	abstract protected function applyFilter(LanguageScriptQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
