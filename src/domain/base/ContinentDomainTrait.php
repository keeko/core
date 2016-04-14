<?php
namespace keeko\core\domain\base;

use keeko\core\model\Continent;
use keeko\core\model\ContinentQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait ContinentDomainTrait {

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

		$query = ContinentQuery::create();

		// sorting
		$sort = $params->getSort(Continent::getSerializer()->getSortFields());
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
		$continent = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $continent]);
	}

	/**
	 * Returns one Continent with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$continent = $this->get($id);

		// check existence
		if ($continent === null) {
			return new NotFound(['message' => 'Continent not found.']);
		}

		return new Found(['model' => $continent]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ContinentDomain
	 * 
	 * @param ContinentQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(ContinentQuery $query, $filter);

	/**
	 * Returns one Continent with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Continent|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$continent = ContinentQuery::create()->findOneById($id);
		$this->pool->set($id, $continent);

		return $continent;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
