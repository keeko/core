<?php
namespace keeko\core\domain\base;

use keeko\core\model\User;
use keeko\core\model\UserQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\core\event\UserEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;
use keeko\core\model\SessionQuery;
use keeko\core\model\GroupQuery;
use keeko\core\model\UserGroupQuery;
use keeko\core\model\ActivityQuery;

/**
 */
trait UserDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Activities to User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addActivities($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$activity = ActivityQuery::create()->findOneById($entry['id']);
			$user->addActivity($activity);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_ACTIVITIES_ADD, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_ACTIVITIES_ADD, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Adds Groups to User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addGroups($id, $data) {
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

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_GROUPS_ADD, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_GROUPS_ADD, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Adds Sessions to User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addSessions($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Session';
			}
			$session = SessionQuery::create()->findOneById($entry['id']);
			$user->addSession($session);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_SESSIONS_ADD, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_SESSIONS_ADD, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

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
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($user)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$user->save();
		$dispatcher->dispatch(UserEvent::POST_CREATE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);
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
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_DELETE, $event);
		$user->delete();

		if ($user->isDeleted()) {
			$dispatcher->dispatch(UserEvent::POST_DELETE, $event);
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
	 * Removes Activities from User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeActivities($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$activity = ActivityQuery::create()->findOneById($entry['id']);
			$user->removeActivity($activity);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_ACTIVITIES_REMOVE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_ACTIVITIES_REMOVE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Removes Groups from User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeGroups($id, $data) {
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

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_GROUPS_REMOVE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_GROUPS_REMOVE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Removes Sessions from User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeSessions($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Session';
			}
			$session = SessionQuery::create()->findOneById($entry['id']);
			$user->removeSession($session);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_SESSIONS_REMOVE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_SESSIONS_REMOVE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

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
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($user)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

		$payload = ['model' => $user];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates Activities on User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateActivities($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// remove all relationships before
		ActivityQuery::create()->filterByActor($user)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			}
			$activity = ActivityQuery::create()->findOneById($entry['id']);
			$user->addActivity($activity);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_ACTIVITIES_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_ACTIVITIES_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Updates Groups on User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateGroups($id, $data) {
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

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_GROUPS_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_GROUPS_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $user]);
		}

		return NotUpdated(['model' => $user]);
	}

	/**
	 * Updates Sessions on User
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateSessions($id, $data) {
		// find
		$user = $this->get($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// remove all relationships before
		SessionQuery::create()->filterByUser($user)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Session';
			}
			$session = SessionQuery::create()->findOneById($entry['id']);
			$user->addSession($session);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new UserEvent($user);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(UserEvent::PRE_SESSIONS_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $user->save();
		$dispatcher->dispatch(UserEvent::POST_SESSIONS_UPDATE, $event);
		$dispatcher->dispatch(UserEvent::POST_SAVE, $event);

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
