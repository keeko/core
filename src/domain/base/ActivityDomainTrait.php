<?php
namespace keeko\core\domain\base;

use keeko\core\model\Activity;
use keeko\core\model\ActivityQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
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
trait ActivityDomainTrait {

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
		if (!$activity->validate()) {
			return new NotValid([
				'errors' => $activity->getValidationFailures()
			]);
		}

		$activity->save();
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
		$activity->delete();

		if ($activity->isDeleted()) {
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
			$activity->save();
			return Updated(['model' => $activity]);
		}

		return NotUpdated(['model' => $activity]);
	}

	/**
	 * Sets the ActivityObject id
	 * 
	 * @param mixed $id
	 * @param mixed $objectId
	 * @return PayloadInterface
	 */
	public function setObjectId($id, $objectId) {
		// find
		$activity = $this->get($id);

		if ($activity === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// update
		if ($activity->getObjectId() !== $objectId) {
			$activity->setObjectId($objectId);
			$activity->save();
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
			$activity->save();
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
		if (!$activity->validate()) {
			return new NotValid([
				'errors' => $activity->getValidationFailures()
			]);
		}

		$rows = $activity->save();
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
