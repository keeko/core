<?php
namespace keeko\core\domain\base;

use keeko\core\model\Country;
use keeko\core\model\CountryQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait CountryDomainTrait {

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
	 */
	public function read($id) {
		// read
		$country = CountryQuery::create()->findOneById($id);

		// check existence
		if ($country === null) {
			$payload = new NotFound(['message' => 'Country not found.']);
		} else {
			$payload = new Found(['model' => $country]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\CountryDomain
	 * 
	 * @param CountryQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(CountryQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
