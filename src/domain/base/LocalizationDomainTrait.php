<?php
namespace keeko\core\domain\base;

use keeko\core\model\Localization;
use keeko\core\model\LocalizationQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;

/**
 */
trait LocalizationDomainTrait {

	/**
	 * Creates a new Localization with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = Localization::getSerializer();
		$localization = $serializer->hydrate(new Localization(), $data);

		// validate
		if (!$localization->validate()) {
			return new NotValid([
				'errors' => $localization->getValidationFailures()
			]);
		}

		$localization->save();
		return new Created(['model' => $localization]);
	}

	/**
	 * Deletes a Localization with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$localization = LocalizationQuery::create()->findOneById($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// delete
		$localization->delete();

		if ($localization->isDeleted()) {
			return new Deleted(['model' => $localization]);
		}

		return new NotDeleted(['message' => 'Could not delete Localization']);
	}

	/**
	 * Returns a paginated result
	 * 
	 * @param Parameters $params
	 */
	public function paginate(Parameters $params) {
		$sysPrefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$defaultSize = $sysPrefs->getPaginationSize();
		$page = $params->getPage('number');
		$size = $params->getPage('size', $defaultSize);

		$query = LocalizationQuery::create();

		// sorting
		$sort = $params->getSort(Localization::getSerializer()->getSortFields());
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
		$localization = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $localization]);
	}

	/**
	 * Returns one Localization with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$localization = LocalizationQuery::create()->findOneById($id);

		// check existence
		if ($localization === null) {
			$payload = new NotFound(['message' => 'Localization not found.']);
		} else {
			$payload = new Found(['model' => $localization]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Localization with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$localization = LocalizationQuery::create()->findOneById($id);

		if ($localization === null) {
			return new NotFound(['message' => 'Localization not found.']);
		}

		// hydrate
		$serializer = Localization::getSerializer();
		$localization = $serializer->hydrate($localization, $data);

		// validate
		if (!$localization->validate()) {
			return new NotValid([
				'errors' => $localization->getValidationFailures()
			]);
		}

		$rows = $localization->save();
		$payload = ['model' => $localization];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\LocalizationDomain
	 * 
	 * @param LocalizationQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(LocalizationQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
