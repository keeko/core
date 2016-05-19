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
use keeko\core\event\ActivityObjectEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;
use keeko\core\model\ActivityQuery;

/**
 */
trait ActivityObjectDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Activities to ActivityObject
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addActivities($id, $data) {
		// find
		$activityObject = $this->get($id);

		if ($activityObject === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$activity = ActivityQuery::create()->findOneById($entry['id']);
			$activityObject->addActivity($activity);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActivityObjectEvent($activityObject);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_ADD, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $activityObject->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_ACTIVITIES_ADD, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $activityObject]);
		}

		return NotUpdated(['model' => $activityObject]);
	}

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
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($activityObject)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActivityObjectEvent($activityObject);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$activityObject->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_CREATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);
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
		$event = new ActivityObjectEvent($activityObject);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_DELETE, $event);
		$activityObject->delete();

		if ($activityObject->isDeleted()) {
			$dispatcher->dispatch(ActivityObjectEvent::POST_DELETE, $event);
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
	 * Removes Activities from ActivityObject
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeActivities($id, $data) {
		// find
		$activityObject = $this->get($id);

		if ($activityObject === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$activity = ActivityQuery::create()->findOneById($entry['id']);
			$activityObject->removeActivity($activity);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActivityObjectEvent($activityObject);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_REMOVE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $activityObject->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_ACTIVITIES_REMOVE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $activityObject]);
		}

		return NotUpdated(['model' => $activityObject]);
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
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($activityObject)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActivityObjectEvent($activityObject);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $activityObject->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		$payload = ['model' => $activityObject];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates Activities on ActivityObject
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateActivities($id, $data) {
		// find
		$activityObject = $this->get($id);

		if ($activityObject === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// remove all relationships before
		ActivityQuery::create()->filterByTarget($activityObject)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$activity = ActivityQuery::create()->findOneById($entry['id']);
			$activityObject->addActivity($activity);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActivityObjectEvent($activityObject);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $activityObject->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_ACTIVITIES_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $activityObject]);
		}

		return NotUpdated(['model' => $activityObject]);
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
