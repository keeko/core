<?php
namespace keeko\core\domain\base;

use keeko\core\model\Group;
use keeko\core\model\GroupQuery;
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
trait GroupDomainTrait {

	/**
	 * Creates a new Group with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = Group::getSerializer();
		$group = $serializer->hydrate(new Group(), $data);

		// validate
		if (!$group->validate()) {
			return new NotValid([
				'errors' => $group->getValidationFailures()
			]);
		}

		$group->save();
		return new Created(['model' => $group]);
	}

	/**
	 * Deletes a Group with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$group = GroupQuery::create()->findOneById($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// delete
		$group->delete();

		if ($group->isDeleted()) {
			return new Deleted(['model' => $group]);
		}

		return new NotDeleted(['message' => 'Could not delete Group']);
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

		$query = GroupQuery::create();

		// sorting
		$sort = $params->getSort(Group::getSerializer()->getSortFields());
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
		$group = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $group]);
	}

	/**
	 * Returns one Group with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$group = GroupQuery::create()->findOneById($id);

		// check existence
		if ($group === null) {
			$payload = new NotFound(['message' => 'Group not found.']);
		} else {
			$payload = new Found(['model' => $group]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Group with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$group = GroupQuery::create()->findOneById($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// hydrate
		$serializer = Group::getSerializer();
		$group = $serializer->hydrate($group, $data);

		// validate
		if (!$group->validate()) {
			return new NotValid([
				'errors' => $group->getValidationFailures()
			]);
		}

		$rows = $group->save();
		$payload = ['model' => $group];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\GroupDomain
	 * 
	 * @param GroupQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(GroupQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
