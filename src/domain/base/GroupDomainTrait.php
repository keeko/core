<?php
namespace keeko\core\domain\base;

use keeko\core\event\GroupEvent;
use keeko\core\model\ActionQuery;
use keeko\core\model\GroupActionQuery;
use keeko\core\model\Group;
use keeko\core\model\GroupQuery;
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
use phootwork\lang\Text;

/**
 */
trait GroupDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Actions to Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addActions($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddActions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(GroupEvent::PRE_ACTIONS_ADD, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(GroupEvent::POST_ACTIONS_ADD, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Adds Users to Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addUsers($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddUsers($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(GroupEvent::PRE_USERS_ADD, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(GroupEvent::POST_USERS_ADD, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Creates a new Group with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$data = $this->normalize($data);
		$serializer = Group::getSerializer();
		$model = $serializer->hydrate(new Group(), $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(GroupEvent::PRE_CREATE, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispatch post save hooks
		$model->save();
		$this->dispatch(GroupEvent::POST_CREATE, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Group with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// delete
		$this->dispatch(GroupEvent::PRE_DELETE, $model);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(GroupEvent::POST_DELETE, $model);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete Group']);
	}

	/**
	 * @param array $data
	 * @return array normalized data
	 */
	public function normalize(array $data) {
		$service = $this->getServiceContainer();
		$attribs = isset($data['attributes']) ? $data['attributes'] : [];


		$data['attributes'] = $attribs;

		return $data;
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
		if ($size == -1) {
			$model = $query->findAll();
		} else {
			$model = $query->paginate($page, $size);
		}

		// run response
		return new Found(['model' => $model]);
	}

	/**
	 * Returns one Group with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * Removes Actions from Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeActions($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveActions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(GroupEvent::PRE_ACTIONS_REMOVE, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(GroupEvent::POST_ACTIONS_REMOVE, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Removes Users from Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeUsers($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveUsers($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(GroupEvent::PRE_USERS_REMOVE, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(GroupEvent::POST_USERS_REMOVE, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// hydrate
		$data = $this->normalize($data);
		$serializer = Group::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(GroupEvent::PRE_UPDATE, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispath post save hooks
		$rows = $model->save();
		$this->dispatch(GroupEvent::POST_UPDATE, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

		$payload = ['model' => $model];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates Actions on Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateActions($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateActions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(GroupEvent::PRE_ACTIONS_UPDATE, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(GroupEvent::POST_ACTIONS_UPDATE, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Updates Users on Group
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateUsers($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Group not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateUsers($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(GroupEvent::PRE_USERS_UPDATE, $model, $data);
		$this->dispatch(GroupEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(GroupEvent::POST_USERS_UPDATE, $model, $data);
		$this->dispatch(GroupEvent::POST_SAVE, $model, $data);

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
		if (is_array($filter)) {

			// filter by fields
			if (isset($filter['fields'])) {
		    	foreach ($filter['fields'] as $column => $value) {
		        	$pos = strpos($column, '.');
		        	if ($pos !== false) {
		        		$rel = NameUtils::toStudlyCase(substr($column, 0, $pos));
		        		$col = substr($column, $pos + 1);
		        		$method = 'use' . $rel . 'Query';
		        		if (method_exists($query, $method)) {
		        			$sub = $query->$method();
		        			$this->applyFilter($sub, ['fields' => [$col => $value]]);
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
		    
		    // filter by features
		    if (isset($filter['features'])) {
		    	$features = new Text($filter['features']);
		    	if ($features->contains('random')) {
		    		$query->addAscendingOrderByColumn('RAND()');
		    	}
		    }
		}

		if (method_exists($this, 'filter')) {
			$this->filter($query, $filter);
		}
	}

	/**
	 * @param string $type
	 * @param Group $model
	 * @param array $data
	 */
	protected function dispatch($type, Group $model, array $data = []) {
		$methods = [
			GroupEvent::PRE_CREATE => 'preCreate',
			GroupEvent::POST_CREATE => 'postCreate',
			GroupEvent::PRE_UPDATE => 'preUpdate',
			GroupEvent::POST_UPDATE => 'postUpdate',
			GroupEvent::PRE_DELETE => 'preDelete',
			GroupEvent::POST_DELETE => 'postDelete',
			GroupEvent::PRE_SAVE => 'preSave',
			GroupEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model, $data);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, new GroupEvent($model));
	}

	/**
	 * Interal mechanism to add Actions to Group
	 * 
	 * @param Group $model
	 * @param mixed $data
	 */
	protected function doAddActions(Group $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			} else {
				$related = ActionQuery::create()->findOneById($entry['id']);
				$model->addAction($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to add Users to Group
	 * 
	 * @param Group $model
	 * @param mixed $data
	 */
	protected function doAddUsers(Group $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for User';
			} else {
				$related = UserQuery::create()->findOneById($entry['id']);
				$model->addUser($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove Actions from Group
	 * 
	 * @param Group $model
	 * @param mixed $data
	 */
	protected function doRemoveActions(Group $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			} else {
				$related = ActionQuery::create()->findOneById($entry['id']);
				$model->removeAction($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove Users from Group
	 * 
	 * @param Group $model
	 * @param mixed $data
	 */
	protected function doRemoveUsers(Group $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for User';
			} else {
				$related = UserQuery::create()->findOneById($entry['id']);
				$model->removeUser($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of Actions on Group
	 * 
	 * @param Group $model
	 * @param mixed $data
	 */
	protected function doUpdateActions(Group $model, $data) {
		// remove all relationships before
		GroupActionQuery::create()->filterByGroup($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			} else {
				$related = ActionQuery::create()->findOneById($entry['id']);
				$model->addAction($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of Users on Group
	 * 
	 * @param Group $model
	 * @param mixed $data
	 */
	protected function doUpdateUsers(Group $model, $data) {
		// remove all relationships before
		UserGroupQuery::create()->filterByGroup($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for User';
			} else {
				$related = UserQuery::create()->findOneById($entry['id']);
				$model->addUser($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

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

		$model = GroupQuery::create()->findOneById($id);
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
