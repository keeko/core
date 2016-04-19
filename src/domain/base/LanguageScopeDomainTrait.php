<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageScope;
use keeko\core\model\LanguageScopeQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageScopeDomainTrait {

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

		$query = LanguageScopeQuery::create();

		// sorting
		$sort = $params->getSort(LanguageScope::getSerializer()->getSortFields());
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
		$languageScope = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageScope]);
	}

	/**
	 * Returns one LanguageScope with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$languageScope = $this->get($id);

		// check existence
		if ($languageScope === null) {
			return new NotFound(['message' => 'LanguageScope not found.']);
		}

		return new Found(['model' => $languageScope]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageScopeDomain
	 * 
	 * @param LanguageScopeQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(LanguageScopeQuery $query, $filter);

	/**
	 * Returns one LanguageScope with the given id from cache
	 * 
	 * @param mixed $id
	 * @return LanguageScope|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$languageScope = LanguageScopeQuery::create()->findOneById($id);
		$this->pool->set($id, $languageScope);

		return $languageScope;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
