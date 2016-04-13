<?php
namespace keeko\core\domain\base;

use keeko\core\model\Currency;
use keeko\core\model\CurrencyQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait CurrencyDomainTrait {

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

		$query = CurrencyQuery::create();

		// sorting
		$sort = $params->getSort(Currency::getSerializer()->getSortFields());
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
		$currency = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $currency]);
	}

	/**
	 * Returns one Currency with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$currency = CurrencyQuery::create()->findOneById($id);

		// check existence
		if ($currency === null) {
			$payload = new NotFound(['message' => 'Currency not found.']);
		} else {
			$payload = new Found(['model' => $currency]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\CurrencyDomain
	 * 
	 * @param CurrencyQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(CurrencyQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
