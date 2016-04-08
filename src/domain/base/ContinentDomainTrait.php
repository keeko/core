<?php
namespace keeko\core\domain\base;

use keeko\core\model\Continent;
use keeko\core\model\ContinentQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait ContinentDomainTrait {

	/**
	 * @param Parameters $params
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
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$continent = ContinentQuery::create()->findOneById($id);

		// check existence
		if ($continent === null) {
			$payload = new NotFound(['message' => 'Continent not found.']);
		} else {
			$payload = new Found(['model' => $continent]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\ContinentDomain
	 * 
	 * @param ContinentQuery $query
	 */
	abstract protected function applyFilter(ContinentQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
