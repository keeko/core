<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageType;
use keeko\core\model\LanguageTypeQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageTypeDomainTrait {

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

		$query = LanguageTypeQuery::create();

		// sorting
		$sort = $params->getSort(LanguageType::getSerializer()->getSortFields());
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
		$languageType = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageType]);
	}

	/**
	 * Returns one LanguageType with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$languageType = LanguageTypeQuery::create()->findOneById($id);

		// check existence
		if ($languageType === null) {
			$payload = new NotFound(['message' => 'LanguageType not found.']);
		} else {
			$payload = new Found(['model' => $languageType]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageTypeDomain
	 * 
	 * @param LanguageTypeQuery $query
	 */
	abstract protected function applyFilter(LanguageTypeQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
