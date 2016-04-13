<?php
namespace keeko\core\domain\base;

use keeko\core\model\Preference;
use keeko\core\model\PreferenceQuery;
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
trait PreferenceDomainTrait {

	/**
	 * Creates a new Preference with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = Preference::getSerializer();
		$preference = $serializer->hydrate(new Preference(), $data);

		// validate
		if (!$preference->validate()) {
			return new NotValid([
				'errors' => $preference->getValidationFailures()
			]);
		}

		$preference->save();
		return new Created(['model' => $preference]);
	}

	/**
	 * Deletes a Preference with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$preference = PreferenceQuery::create()->findOneById($id);

		if ($preference === null) {
			return new NotFound(['message' => 'Preference not found.']);
		}

		// delete
		$preference->delete();

		if ($preference->isDeleted()) {
			return new Deleted(['model' => $preference]);
		}

		return new NotDeleted(['message' => 'Could not delete Preference']);
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

		$query = PreferenceQuery::create();

		// sorting
		$sort = $params->getSort(Preference::getSerializer()->getSortFields());
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
		$preference = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $preference]);
	}

	/**
	 * Returns one Preference with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$preference = PreferenceQuery::create()->findOneById($id);

		// check existence
		if ($preference === null) {
			$payload = new NotFound(['message' => 'Preference not found.']);
		} else {
			$payload = new Found(['model' => $preference]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Preference with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$preference = PreferenceQuery::create()->findOneById($id);

		if ($preference === null) {
			return new NotFound(['message' => 'Preference not found.']);
		}

		// hydrate
		$serializer = Preference::getSerializer();
		$preference = $serializer->hydrate($preference, $data);

		// validate
		if (!$preference->validate()) {
			return new NotValid([
				'errors' => $preference->getValidationFailures()
			]);
		}

		$rows = $preference->save();
		$payload = ['model' => $preference];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\PreferenceDomain
	 * 
	 * @param PreferenceQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(PreferenceQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
