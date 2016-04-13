<?php
namespace keeko\core\domain\base;

use keeko\core\model\Subdivision;
use keeko\core\model\SubdivisionQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait SubdivisionDomainTrait {

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

		$query = SubdivisionQuery::create();

		// sorting
		$sort = $params->getSort(Subdivision::getSerializer()->getSortFields());
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
		$subdivision = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $subdivision]);
	}

	/**
	 * Returns one Subdivision with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$subdivision = SubdivisionQuery::create()->findOneById($id);

		// check existence
		if ($subdivision === null) {
			$payload = new NotFound(['message' => 'Subdivision not found.']);
		} else {
			$payload = new Found(['model' => $subdivision]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\SubdivisionDomain
	 * 
	 * @param SubdivisionQuery $query
	 */
	abstract protected function applyFilter(SubdivisionQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
