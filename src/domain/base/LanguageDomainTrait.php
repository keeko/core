<?php
namespace keeko\core\domain\base;

use keeko\core\model\Language;
use keeko\core\model\LanguageQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;

/**
 */
trait LanguageDomainTrait {

	/**
	 * @param Parameters $params
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
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$language = LanguageQuery::create()->findOneById($id);

		// check existence
		if ($language === null) {
			$payload = new NotFound(['message' => 'Language not found.']);
		} else {
			$payload = new Found(['model' => $language]);
		}

		// run response
		return $payload;
	}

	/**
	 * Implement this functionality at keeko\core\domain\LanguageDomain
	 * 
	 * @param LanguageQuery $query
	 */
	abstract protected function applyFilter(LanguageQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
