<?php
namespace keeko\core\domain\base;

use keeko\core\model\RegionArea;
use keeko\core\model\RegionAreaQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait RegionAreaDomainTrait {

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

		$query = RegionAreaQuery::create();

		// sorting
		$sort = $params->getSort(RegionArea::getSerializer()->getSortFields());
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
		$regionArea = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $regionArea]);
	}

	/**
	 * Returns one RegionArea with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$regionArea = $this->get($id);

		// check existence
		if ($regionArea === null) {
			return new NotFound(['message' => 'RegionArea not found.']);
		}

		return new Found(['model' => $regionArea]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\RegionAreaDomain
	 * 
	 * @param RegionAreaQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(RegionAreaQuery $query, $filter);

	/**
	 * Returns one RegionArea with the given id from cache
	 * 
	 * @param mixed $id
	 * @return RegionArea|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$regionArea = RegionAreaQuery::create()->findOneById($id);
		$this->pool->set($id, $regionArea);

		return $regionArea;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
