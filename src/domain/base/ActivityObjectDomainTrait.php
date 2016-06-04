<?php
namespace keeko\core\domain\base;

use keeko\core\event\ActivityObjectEvent;
use keeko\core\model\ActivityObjectQuery;
use keeko\core\model\ActivityObject;
use keeko\core\model\ActivityQuery;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotDeleted;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\domain\payload\Updated;
use keeko\framework\service\ServiceContainer;
use keeko\framework\utils\NameUtils;
use keeko\framework\utils\Parameters;
use phootwork\collection\Map;

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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$related = ActivityQuery::create()->findOneById($entry['id']);
			$model->addActivity($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActivityObjectEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_ADD, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_ACTIVITIES_ADD, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $serializer->hydrate(new ActivityObject(), $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActivityObjectEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$model->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_CREATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);
		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a ActivityObject with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// delete
		$event = new ActivityObjectEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_DELETE, $event);
		$model->delete();

		if ($model->isDeleted()) {
			$dispatcher->dispatch(ActivityObjectEvent::POST_DELETE, $event);
			return new Deleted(['model' => $model]);
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
		$model = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $model]);
	}

	/**
	 * Returns one ActivityObject with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		return new Found(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$related = ActivityQuery::create()->findOneById($entry['id']);
			$model->removeActivity($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActivityObjectEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_REMOVE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_ACTIVITIES_REMOVE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// hydrate
		$serializer = ActivityObject::getSerializer();
		$model = $serializer->hydrate($model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActivityObjectEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		$payload = ['model' => $model];

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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// remove all relationships before
		ActivityQuery::create()->filterByTarget($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$related = ActivityQuery::create()->findOneById($entry['id']);
			$model->addActivity($related);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActivityObjectEvent($model);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$dispatcher->dispatch(ActivityObjectEvent::POST_ACTIVITIES_UPDATE, $event);
		$dispatcher->dispatch(ActivityObjectEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * @param mixed $query
	 * @param mixed $filter
	 * @return void
	 */
	protected function applyFilter($query, $filter) {
		foreach ($filter as $column => $value) {
			$pos = strpos($column, '.');
			if ($pos !== false) {
				$rel = NameUtils::toStudlyCase(substr($column, 0, $pos));
				$col = substr($column, $pos + 1);
				$method = 'use' . $rel . 'Query';
				if (method_exists($query, $method)) {
					$sub = $query->$method();
					$this->applyFilter($sub, [$col => $value]);
					$sub->endUse();
				}
			} else {
				$method = 'filterBy' . NameUtils::toStudlyCase($column);
				if (method_exists($query, $method)) {
					$query->$method($value);
				}
			}
		}
	}

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

		$model = ActivityObjectQuery::create()->findOneById($id);
		$this->pool->set($id, $model);

		return $model;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
