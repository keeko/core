<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageType;
use keeko\core\model\LanguageTypeQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageTypeDomainTrait {

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

		$query = LanguageTypeQuery::create();

		// sorting
		$sort = $params->getSort(LanguageType::getSerializer()->getSortFields());
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
		$languageType = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageType]);
	}

	/**
	 * Returns one LanguageType with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$languageType = $this->get($id);

		// check existence
		if ($languageType === null) {
			return new NotFound(['message' => 'LanguageType not found.']);
		}

		return new Found(['model' => $languageType]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageTypeDomain
	 * 
	 * @param LanguageTypeQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(LanguageTypeQuery $query, $filter);

	/**
	 * Returns one LanguageType with the given id from cache
	 * 
	 * @param mixed $id
	 * @return LanguageType|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$languageType = LanguageTypeQuery::create()->findOneById($id);
		$this->pool->set($id, $languageType);

		return $languageType;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
