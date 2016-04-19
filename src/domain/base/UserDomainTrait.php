<?php
namespace keeko\core\domain\base;

use keeko\core\model\User;
use keeko\core\model\UserQuery;
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
use keeko\core\model\GroupQuery;
use keeko\core\model\UserGroupQuery;

/**
 */
trait UserDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Group to User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addGroup($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$user->addGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $user->save();

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Creates a new User with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = User::getSerializer();
		$user = $serializer->hydrate(new User(), $data);

		// validate
		if (!$user->validate()) {
			return new NotValid([
				'errors' => $user->getValidationFailures()
			]);
		}

		$user->save();
		return new Created(['model' => $user]);
	}

	/**
	 * Deletes a User with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// delete
		$user->delete();

		if ($user->isDeleted()) {
			return new Deleted(['model' => $user]);
		}

		return new NotDeleted(['message' => 'Could not delete User']);
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

		$query = UserQuery::create();

		// sorting
		$sort = $params->getSort(User::getSerializer()->getSortFields());
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
		$user = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $user]);
	}

	/**
	 * Returns one User with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$user = $this->get($id);

		// check existence
		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		return new Found(['model' => $user]);
	}

	/**
	 * Removes Group from User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeGroup($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$user->removeGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $user->save();

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Updates a User with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// hydrate
		$serializer = User::getSerializer();
		$user = $serializer->hydrate($user, $data);

		// validate
		if (!$user->validate()) {
			return new NotValid([
				'errors' => $user->getValidationFailures()
			]);
		}

		$rows = $user->save();
		$payload = ['model' => $user];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates Group on User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateGroup($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// remove all relationships before
		UserGroupQuery::create()->filterByUser($user)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$user->addGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $user->save();

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\UserDomain
	 * 
	 * @param UserQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(UserQuery $query, $filter);

	/**
	 * Returns one User with the given id from cache
	 * 
	 * @param mixed $id
	 * @return User|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$user = UserQuery::create()->findOneById($id);
		$this->pool->set($id, $user);

		return $user;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
