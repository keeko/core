<?php
namespace keeko\core\domain\base;

use keeko\core\model\Language;
use keeko\core\model\LanguageQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageDomainTrait {

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

		$query = LanguageQuery::create();

		// sorting
		$sort = $params->getSort(Language::getSerializer()->getSortFields());
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
		$language = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $language]);
	}

	/**
	 * Returns one Language with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$language = $this->get($id);

		// check existence
		if ($language === null) {
			return new NotFound(['message' => 'Language not found.']);
		}

		return new Found(['model' => $language]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageDomain
	 * 
	 * @param LanguageQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(LanguageQuery $query, $filter);

	/**
	 * Returns one Language with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Language|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$language = LanguageQuery::create()->findOneById($id);
		$this->pool->set($id, $language);

		return $language;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
