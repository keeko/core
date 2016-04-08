<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageVariant;
use keeko\core\model\LanguageVariantQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageVariantDomainTrait {

	/**
	 * @param Parameters $params
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
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$languageVariant = LanguageVariantQuery::create()->findOneById($id);

		// check existence
		if ($languageVariant === null) {
			$payload = new NotFound(['message' => 'LanguageVariant not found.']);
		} else {
			$payload = new Found(['model' => $languageVariant]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageVariantDomain
	 * 
	 * @param LanguageVariantQuery $query
	 */
	abstract protected function applyFilter(LanguageVariantQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
