<?php
namespace keeko\core\domain\base;

use keeko\core\model\RegionType;
use keeko\core\model\RegionTypeQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait RegionTypeDomainTrait {

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

		$query = RegionTypeQuery::create();

		// sorting
		$sort = $params->getSort(RegionType::getSerializer()->getSortFields());
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
		$regionType = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $regionType]);
	}

	/**
	 * Returns one RegionType with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$regionType = $this->get($id);

		// check existence
		if ($regionType === null) {
			return new NotFound(['message' => 'RegionType not found.']);
		}

		return new Found(['model' => $regionType]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\RegionTypeDomain
	 * 
	 * @param RegionTypeQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(RegionTypeQuery $query, $filter);

	/**
	 * Returns one RegionType with the given id from cache
	 * 
	 * @param mixed $id
	 * @return RegionType|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$regionType = RegionTypeQuery::create()->findOneById($id);
		$this->pool->set($id, $regionType);

		return $regionType;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
