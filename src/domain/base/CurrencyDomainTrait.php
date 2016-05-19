<?php
namespace keeko\core\domain\base;

use keeko\core\model\Currency;
use keeko\core\model\CurrencyQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait CurrencyDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Returns a paginated result
	 * 
	 * @param Parameters $params
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$currency = $this->get($id);

		// check existence
		if ($currency === null) {
			return new NotFound(['message' => 'Currency not found.']);
		}

		return new Found(['model' => $currency]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\CurrencyDomain
	 * 
	 * @param CurrencyQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(CurrencyQuery $query, $filter);

	/**
	 * Returns one Currency with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Currency|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$currency = CurrencyQuery::create()->findOneById($id);
		$this->pool->set($id, $currency);

		return $currency;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
