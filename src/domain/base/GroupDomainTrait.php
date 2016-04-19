<?php
namespace keeko\core\domain\base;

use keeko\core\model\Group;
use keeko\core\model\GroupQuery;
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
use keeko\core\model\UserQuery;
use keeko\core\model\UserGroupQuery;
use keeko\core\model\ActionQuery;
use keeko\core\model\GroupActionQuery;

/**
 */
trait GroupDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Action to Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addAction($id, $data) {
		// find
		$group = $this->get($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			}
			$action = ActionQuery::create()->findOneById($entry['id']);
			$group->addAction($action);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $group->save();

		if ($rows > 0) {
			return Updated(['model' => $group]);
		}

		return NotUpdated(['model' => $group]);
	}

	/**
	 * Adds User to Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addUser($id, $data) {
		// find
		$group = $this->get($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for User';
			}
			$user = UserQuery::create()->findOneById($entry['id']);
			$group->addUser($user);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $group->save();

		if ($rows > 0) {
			return Updated(['model' => $group]);
		}

		return NotUpdated(['model' => $group]);
	}

	/**
	 * Creates a new Group with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$group = $this->get($id);

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
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$group = $this->get($id);

		// check existence
		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		return new Found(['model' => $group]);
	}

	/**
	 * Removes Action from Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeAction($id, $data) {
		// find
		$group = $this->get($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			}
			$action = ActionQuery::create()->findOneById($entry['id']);
			$group->removeAction($action);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $group->save();

		if ($rows > 0) {
			return Updated(['model' => $group]);
		}

		return NotUpdated(['model' => $group]);
	}

	/**
	 * Removes User from Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeUser($id, $data) {
		// find
		$group = $this->get($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for User';
			}
			$user = UserQuery::create()->findOneById($entry['id']);
			$group->removeUser($user);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $group->save();

		if ($rows > 0) {
			return Updated(['model' => $group]);
		}

		return NotUpdated(['model' => $group]);
	}

	/**
	 * Updates a Group with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$group = $this->get($id);

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
	 * Updates Action on Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateAction($id, $data) {
		// find
		$group = $this->get($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// remove all relationships before
		GroupActionQuery::create()->filterByGroup($group)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			}
			$action = ActionQuery::create()->findOneById($entry['id']);
			$group->addAction($action);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $group->save();

		if ($rows > 0) {
			return Updated(['model' => $group]);
		}

		return NotUpdated(['model' => $group]);
	}

	/**
	 * Updates User on Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateUser($id, $data) {
		// find
		$group = $this->get($id);

		if ($group === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// remove all relationships before
		UserGroupQuery::create()->filterByGroup($group)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for User';
			}
			$user = UserQuery::create()->findOneById($entry['id']);
			$group->addUser($user);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $group->save();

		if ($rows > 0) {
			return Updated(['model' => $group]);
		}

		return NotUpdated(['model' => $group]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\GroupDomain
	 * 
	 * @param GroupQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(GroupQuery $query, $filter);

	/**
	 * Returns one Group with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Group|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$group = GroupQuery::create()->findOneById($id);
		$this->pool->set($id, $group);

		return $group;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
