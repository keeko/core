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
		$payload = ['model' => $group];

		if ($group->isDeleted()) {
			return new Deleted($payload);
		}

		return new NotDeleted($payload);
	}

	/**
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
	 */
	abstract protected function applyFilter(GroupQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
