<?php
namespace keeko\core\domain\base;

use keeko\core\model\Country;
use keeko\core\model\CountryQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait CountryDomainTrait {

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

		$query = CountryQuery::create();

		// sorting
		$sort = $params->getSort(Country::getSerializer()->getSortFields());
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
		$country = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $country]);
	}

	/**
	 * Returns one Country with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$country = $this->get($id);

		// check existence
		if ($country === null) {
			return new NotFound(['message' => 'Country not found.']);
		}

		return new Found(['model' => $country]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\CountryDomain
	 * 
	 * @param CountryQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(CountryQuery $query, $filter);

	/**
	 * Returns one Country with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Country|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$country = CountryQuery::create()->findOneById($id);
		$this->pool->set($id, $country);

		return $country;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
