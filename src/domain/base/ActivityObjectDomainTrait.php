<?php
namespace keeko\core\domain\base;

use keeko\core\model\ActivityObject;
use keeko\core\model\ActivityObjectQuery;
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
trait ActivityObjectDomainTrait {

	/**
	 * Creates a new ActivityObject with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = ActivityObject::getSerializer();
		$activityObject = $serializer->hydrate(new ActivityObject(), $data);

		// validate
		if (!$activityObject->validate()) {
			return new NotValid([
				'errors' => $activityObject->getValidationFailures()
			]);
		}

		$activityObject->save();
		return new Created(['model' => $activityObject]);
	}

	/**
	 * Deletes a ActivityObject with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$activityObject = ActivityObjectQuery::create()->findOneById($id);

		if ($activityObject === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// delete
		$activityObject->delete();

		if ($activityObject->isDeleted()) {
			return new Deleted(['model' => $activityObject]);
		}

		return new NotDeleted(['message' => 'Could not delete ActivityObject']);
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

		$query = ActivityObjectQuery::create();

		// sorting
		$sort = $params->getSort(ActivityObject::getSerializer()->getSortFields());
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
		$activityObject = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $activityObject]);
	}

	/**
	 * Returns one ActivityObject with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$activityObject = ActivityObjectQuery::create()->findOneById($id);

		// check existence
		if ($activityObject === null) {
			$payload = new NotFound(['message' => 'ActivityObject not found.']);
		} else {
			$payload = new Found(['model' => $activityObject]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a ActivityObject with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$activityObject = ActivityObjectQuery::create()->findOneById($id);

		if ($activityObject === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// hydrate
		$serializer = ActivityObject::getSerializer();
		$activityObject = $serializer->hydrate($activityObject, $data);

		// validate
		if (!$activityObject->validate()) {
			return new NotValid([
				'errors' => $activityObject->getValidationFailures()
			]);
		}

		$rows = $activityObject->save();
		$payload = ['model' => $activityObject];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ActivityObjectDomain
	 * 
	 * @param ActivityObjectQuery $query
	 */
	abstract protected function applyFilter(ActivityObjectQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
