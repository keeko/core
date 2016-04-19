<?php
namespace keeko\core\domain\base;

use keeko\core\model\ActivityObject;
use keeko\core\model\ActivityObjectQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
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
	 */
	protected $pool;

	/**
	 * Creates a new ActivityObject with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$activityObject = $this->get($id);

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
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$activityObject = $this->get($id);

		// check existence
		if ($activityObject === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		return new Found(['model' => $activityObject]);
	}

	/**
	 * Updates a ActivityObject with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$activityObject = $this->get($id);

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
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(ActivityObjectQuery $query, $filter);

	/**
	 * Returns one ActivityObject with the given id from cache
	 * 
	 * @param mixed $id
	 * @return ActivityObject|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$activityObject = ActivityObjectQuery::create()->findOneById($id);
		$this->pool->set($id, $activityObject);

		return $activityObject;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
