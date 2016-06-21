<?php
namespace keeko\core\domain\base;

use keeko\core\event\UserEvent;
use keeko\core\model\ActivityQuery;
use keeko\core\model\GroupQuery;
use keeko\core\model\SessionQuery;
use keeko\core\model\User;
use keeko\core\model\UserGroupQuery;
use keeko\core\model\UserQuery;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotDeleted;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\domain\payload\Updated;
use keeko\framework\exceptions\ErrorsException;
use keeko\framework\service\ServiceContainer;
use keeko\framework\utils\NameUtils;
use keeko\framework\utils\Parameters;
use phootwork\collection\Map;

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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddActivities($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_ACTIVITIES_ADD, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_ACTIVITIES_ADD, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddGroups($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_GROUPS_ADD, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_GROUPS_ADD, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddSessions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_SESSIONS_ADD, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_SESSIONS_ADD, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $serializer->hydrate(new User(), $data);
		$this->hydrateRelationships($model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_CREATE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$model->save();
		$this->dispatch(UserEvent::POST_CREATE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);
		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a User with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// delete
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_DELETE, $event);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(UserEvent::POST_DELETE, $event);
			return new Deleted(['model' => $model]);
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
		$model = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $model]);
	}

	/**
	 * Returns one User with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		return new Found(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveActivities($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_ACTIVITIES_REMOVE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_ACTIVITIES_REMOVE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveGroups($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_GROUPS_REMOVE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_GROUPS_REMOVE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveSessions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_SESSIONS_REMOVE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_SESSIONS_REMOVE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// hydrate
		$serializer = User::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_UPDATE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_UPDATE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		$payload = ['model' => $model];

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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateActivities($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_ACTIVITIES_UPDATE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_ACTIVITIES_UPDATE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateGroups($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_GROUPS_UPDATE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_GROUPS_UPDATE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateSessions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$event = new UserEvent($model);
		$this->dispatch(UserEvent::PRE_SESSIONS_UPDATE, $event);
		$this->dispatch(UserEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(UserEvent::POST_SESSIONS_UPDATE, $event);
		$this->dispatch(UserEvent::POST_SAVE, $event);

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
	 * @param string $type
	 * @param UserEvent $event
	 */
	protected function dispatch($type, UserEvent $event) {
		$model = $event->getUser();
		$methods = [
			UserEvent::PRE_CREATE => 'preCreate',
			UserEvent::POST_CREATE => 'postCreate',
			UserEvent::PRE_UPDATE => 'preUpdate',
			UserEvent::POST_UPDATE => 'postUpdate',
			UserEvent::PRE_DELETE => 'preDelete',
			UserEvent::POST_DELETE => 'postDelete',
			UserEvent::PRE_SAVE => 'preSave',
			UserEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, $event);
	}

	/**
	 * Interal mechanism to add Activities to User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doAddActivities(User $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			} else {
				$related = ActivityQuery::create()->findOneById($entry['id']);
				$model->addActivity($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to add Groups to User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doAddGroups(User $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			} else {
				$related = GroupQuery::create()->findOneById($entry['id']);
				$model->addGroup($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to add Sessions to User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doAddSessions(User $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Session';
			} else {
				$related = SessionQuery::create()->findOneById($entry['id']);
				$model->addSession($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove Activities from User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doRemoveActivities(User $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			} else {
				$related = ActivityQuery::create()->findOneById($entry['id']);
				$model->removeActivity($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove Groups from User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doRemoveGroups(User $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			} else {
				$related = GroupQuery::create()->findOneById($entry['id']);
				$model->removeGroup($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove Sessions from User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doRemoveSessions(User $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Session';
			} else {
				$related = SessionQuery::create()->findOneById($entry['id']);
				$model->removeSession($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of Activities on User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doUpdateActivities(User $model, $data) {
		// remove all relationships before
		ActivityQuery::create()->filterByActor($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Activity';
			} else {
				$related = ActivityQuery::create()->findOneById($entry['id']);
				$model->addActivity($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of Groups on User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doUpdateGroups(User $model, $data) {
		// remove all relationships before
		UserGroupQuery::create()->filterByUser($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			} else {
				$related = GroupQuery::create()->findOneById($entry['id']);
				$model->addGroup($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of Sessions on User
	 * 
	 * @param User $model
	 * @param mixed $data
	 */
	protected function doUpdateSessions(User $model, $data) {
		// remove all relationships before
		SessionQuery::create()->filterByUser($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Session';
			} else {
				$related = SessionQuery::create()->findOneById($entry['id']);
				$model->addSession($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

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

		$model = UserQuery::create()->findOneById($id);
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
