<?php
namespace keeko\core\domain\base;

use keeko\core\model\Activity;
use keeko\core\model\ActivityQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\core\event\ActivityEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;

/**
 */
trait ActivityDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new Activity with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Activity::getSerializer();
		$activity = $serializer->hydrate(new Activity(), $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($activity)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActivityEvent($activity);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(ActivityEvent::PRE_SAVE, $event);
		$activity->save();
		$dispatcher->dispatch(ActivityEvent::POST_CREATE, $event);
		$dispatcher->dispatch(ActivityEvent::POST_SAVE, $event);
		return new Created(['model' => $activity]);
	}

	/**
	 * Deletes a Activity with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$activity = $this->get($id);

		if ($activity === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// delete
		$event = new ActivityEvent($activity);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityEvent::PRE_DELETE, $event);
		$activity->delete();

		if ($activity->isDeleted()) {
			$dispatcher->dispatch(ActivityEvent::POST_DELETE, $event);
			return new Deleted(['model' => $activity]);
		}

		return new NotDeleted(['message' => 'Could not delete Activity']);
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

		$query = ActivityQuery::create();

		// sorting
		$sort = $params->getSort(Activity::getSerializer()->getSortFields());
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
		$activity = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $activity]);
	}

	/**
	 * Returns one Activity with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$activity = $this->get($id);

		// check existence
		if ($activity === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		return new Found(['model' => $activity]);
	}

	/**
	 * Sets the User id
	 * 
	 * @param mixed $id
	 * @param mixed $actorId
	 * @return PayloadInterface
	 */
	public function setActorId($id, $actorId) {
		// find
		$activity = $this->get($id);

		if ($activity === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// update
		if ($activity->getActorId() !== $actorId) {
			$activity->setActorId($actorId);

			$event = new ActivityEvent($activity);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(ActivityEvent::PRE_ACTOR_UPDATE, $event);
			$dispatcher->dispatch(ActivityEvent::PRE_SAVE, $event);
			$activity->save();
			$dispatcher->dispatch(ActivityEvent::POST_ACTOR_UPDATE, $event);
			$dispatcher->dispatch(ActivityEvent::POST_SAVE, $event);
			
			return Updated(['model' => $activity]);
		}

		return NotUpdated(['model' => $activity]);
	}

	/**
	 * Sets the ActivityObject id
	 * 
	 * @param mixed $id
	 * @param mixed $targetId
	 * @return PayloadInterface
	 */
	public function setTargetId($id, $targetId) {
		// find
		$activity = $this->get($id);

		if ($activity === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// update
		if ($activity->getTargetId() !== $targetId) {
			$activity->setTargetId($targetId);

			$event = new ActivityEvent($activity);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(ActivityEvent::PRE_TARGET_UPDATE, $event);
			$dispatcher->dispatch(ActivityEvent::PRE_SAVE, $event);
			$activity->save();
			$dispatcher->dispatch(ActivityEvent::POST_TARGET_UPDATE, $event);
			$dispatcher->dispatch(ActivityEvent::POST_SAVE, $event);
			
			return Updated(['model' => $activity]);
		}

		return NotUpdated(['model' => $activity]);
	}

	/**
	 * Updates a Activity with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$activity = $this->get($id);

		if ($activity === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// hydrate
		$serializer = Activity::getSerializer();
		$activity = $serializer->hydrate($activity, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($activity)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActivityEvent($activity);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActivityEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(ActivityEvent::PRE_SAVE, $event);
		$rows = $activity->save();
		$dispatcher->dispatch(ActivityEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(ActivityEvent::POST_SAVE, $event);

		$payload = ['model' => $activity];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ActivityDomain
	 * 
	 * @param ActivityQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(ActivityQuery $query, $filter);

	/**
	 * Returns one Activity with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Activity|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$activity = ActivityQuery::create()->findOneById($id);
		$this->pool->set($id, $activity);

		return $activity;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
