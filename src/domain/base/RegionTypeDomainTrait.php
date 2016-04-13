<?php
namespace keeko\core\domain\base;

use keeko\core\model\RegionType;
use keeko\core\model\RegionTypeQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait RegionTypeDomainTrait {

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
	 */
	public function read($id) {
		// read
		$regionType = RegionTypeQuery::create()->findOneById($id);

		// check existence
		if ($regionType === null) {
			$payload = new NotFound(['message' => 'RegionType not found.']);
		} else {
			$payload = new Found(['model' => $regionType]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\RegionTypeDomain
	 * 
	 * @param RegionTypeQuery $query
	 */
	abstract protected function applyFilter(RegionTypeQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
