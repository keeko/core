<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageScript;
use keeko\core\model\LanguageScriptQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageScriptDomainTrait {

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

		$query = LanguageScriptQuery::create();

		// sorting
		$sort = $params->getSort(LanguageScript::getSerializer()->getSortFields());
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
		$languageScript = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageScript]);
	}

	/**
	 * Returns one LanguageScript with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$languageScript = $this->get($id);

		// check existence
		if ($languageScript === null) {
			return new NotFound(['message' => 'LanguageScript not found.']);
		}

		return new Found(['model' => $languageScript]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageScriptDomain
	 * 
	 * @param LanguageScriptQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(LanguageScriptQuery $query, $filter);

	/**
	 * Returns one LanguageScript with the given id from cache
	 * 
	 * @param mixed $id
	 * @return LanguageScript|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$languageScript = LanguageScriptQuery::create()->findOneById($id);
		$this->pool->set($id, $languageScript);

		return $languageScript;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
