<?php
namespace keeko\core\domain\base;

use keeko\core\model\Subdivision;
use keeko\core\model\SubdivisionQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait SubdivisionDomainTrait {

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
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$subdivision = $this->get($id);

		// check existence
		if ($subdivision === null) {
			return new NotFound(['message' => 'Subdivision not found.']);
		}

		return new Found(['model' => $subdivision]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\SubdivisionDomain
	 * 
	 * @param SubdivisionQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(SubdivisionQuery $query, $filter);

	/**
	 * Returns one Subdivision with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Subdivision|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$subdivision = SubdivisionQuery::create()->findOneById($id);
		$this->pool->set($id, $subdivision);

		return $subdivision;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
