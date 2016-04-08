<?php
namespace keeko\core\domain\base;

use keeko\core\model\LanguageFamily;
use keeko\core\model\LanguageFamilyQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageFamilyDomainTrait {

	/**
	 * @param Parameters $params
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
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$languageFamily = LanguageFamilyQuery::create()->findOneById($id);

		// check existence
		if ($languageFamily === null) {
			$payload = new NotFound(['message' => 'LanguageFamily not found.']);
		} else {
			$payload = new Found(['model' => $languageFamily]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageFamilyDomain
	 * 
	 * @param LanguageFamilyQuery $query
	 */
	abstract protected function applyFilter(LanguageFamilyQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
