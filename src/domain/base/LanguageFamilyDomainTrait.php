<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageFamily;
use keeko\core\model\LanguageFamilyQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageFamilyDomainTrait {

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

		$query = LanguageFamilyQuery::create();

		// sorting
		$sort = $params->getSort(LanguageFamily::getSerializer()->getSortFields());
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
		$languageFamily = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageFamily]);
	}

	/**
	 * Returns one LanguageFamily with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$languageFamily = $this->get($id);

		// check existence
		if ($languageFamily === null) {
			return new NotFound(['message' => 'LanguageFamily not found.']);
		}

		return new Found(['model' => $languageFamily]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageFamilyDomain
	 * 
	 * @param LanguageFamilyQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(LanguageFamilyQuery $query, $filter);

	/**
	 * Returns one LanguageFamily with the given id from cache
	 * 
	 * @param mixed $id
	 * @return LanguageFamily|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$languageFamily = LanguageFamilyQuery::create()->findOneById($id);
		$this->pool->set($id, $languageFamily);

		return $languageFamily;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
