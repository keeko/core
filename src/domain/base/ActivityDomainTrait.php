<?php
namespace keeko\core\domain\base;

use keeko\core\model\Activity;
use keeko\core\model\ActivityQuery;
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
trait ActivityDomainTrait {

	/**
	 * Creates a new Activity with the provided data
	 * 
	 * @param mixed $data
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
	 */
	public function delete($id) {
		// find
		$activity = ActivityQuery::create()->findOneById($id);

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
	 */
	public function read($id) {
		// read
		$activity = ActivityQuery::create()->findOneById($id);

		// check existence
		if ($activity === null) {
			$payload = new NotFound(['message' => 'Activity not found.']);
		} else {
			$payload = new Found(['model' => $activity]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Activity with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$activity = ActivityQuery::create()->findOneById($id);

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
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
