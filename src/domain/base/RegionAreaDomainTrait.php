<?php
namespace keeko\core\domain\base;

use keeko\core\model\RegionArea;
use keeko\core\model\RegionAreaQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait RegionAreaDomainTrait {

	/**
	 * @param Parameters $params
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
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$regionArea = RegionAreaQuery::create()->findOneById($id);

		// check existence
		if ($regionArea === null) {
			$payload = new NotFound(['message' => 'RegionArea not found.']);
		} else {
			$payload = new Found(['model' => $regionArea]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\RegionAreaDomain
	 * 
	 * @param RegionAreaQuery $query
	 */
	abstract protected function applyFilter(RegionAreaQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
