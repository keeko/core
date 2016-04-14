<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageVariant;
use keeko\core\model\LanguageVariantQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageVariantDomainTrait {

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

		$query = LanguageVariantQuery::create();

		// sorting
		$sort = $params->getSort(LanguageVariant::getSerializer()->getSortFields());
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
		$languageVariant = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $languageVariant]);
	}

	/**
	 * Returns one LanguageVariant with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$languageVariant = $this->get($id);

		// check existence
		if ($languageVariant === null) {
			return new NotFound(['message' => 'LanguageVariant not found.']);
		}

		return new Found(['model' => $languageVariant]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageVariantDomain
	 * 
	 * @param LanguageVariantQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(LanguageVariantQuery $query, $filter);

	/**
	 * Returns one LanguageVariant with the given id from cache
	 * 
	 * @param mixed $id
	 * @return LanguageVariant|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$languageVariant = LanguageVariantQuery::create()->findOneById($id);
		$this->pool->set($id, $languageVariant);

		return $languageVariant;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
