<?php
namespace keeko\core\domain\base;

use keeko\core\model\Action;
use keeko\core\model\ActionQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\core\event\ActionEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;
use keeko\core\model\GroupQuery;
use keeko\core\model\GroupActionQuery;
use keeko\core\model\ApiQuery;

/**
 */
trait ActionDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Apis to Action
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addApis($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Api';
			}
			$api = ApiQuery::create()->findOneById($entry['id']);
			$action->addApi($api);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_APIS_ADD, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$rows = $action->save();
		$dispatcher->dispatch(ActionEvent::POST_APIS_ADD, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Adds Groups to Action
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addGroups($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$action->addGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_GROUPS_ADD, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$rows = $action->save();
		$dispatcher->dispatch(ActionEvent::POST_GROUPS_ADD, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Creates a new Action with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Action::getSerializer();
		$action = $serializer->hydrate(new Action(), $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($action)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$action->save();
		$dispatcher->dispatch(ActionEvent::POST_CREATE, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);
		return new Created(['model' => $action]);
	}

	/**
	 * Deletes a Action with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// delete
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_DELETE, $event);
		$action->delete();

		if ($action->isDeleted()) {
			$dispatcher->dispatch(ActionEvent::POST_DELETE, $event);
			return new Deleted(['model' => $action]);
		}

		return new NotDeleted(['message' => 'Could not delete Action']);
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

		$query = ActionQuery::create();

		// sorting
		$sort = $params->getSort(Action::getSerializer()->getSortFields());
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
		$action = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $action]);
	}

	/**
	 * Returns one Action with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$action = $this->get($id);

		// check existence
		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		return new Found(['model' => $action]);
	}

	/**
	 * Removes Apis from Action
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeApis($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Api';
			}
			$api = ApiQuery::create()->findOneById($entry['id']);
			$action->removeApi($api);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_APIS_REMOVE, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$rows = $action->save();
		$dispatcher->dispatch(ActionEvent::POST_APIS_REMOVE, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Removes Groups from Action
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeGroups($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$action->removeGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_GROUPS_REMOVE, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$rows = $action->save();
		$dispatcher->dispatch(ActionEvent::POST_GROUPS_REMOVE, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Sets the Module id
	 * 
	 * @param mixed $id
	 * @param mixed $moduleId
	 * @return PayloadInterface
	 */
	public function setModuleId($id, $moduleId) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// update
		if ($action->getModuleId() !== $moduleId) {
			$action->setModuleId($moduleId);

			$event = new ActionEvent($action);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(ActionEvent::PRE_MODULE_UPDATE, $event);
			$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
			$action->save();
			$dispatcher->dispatch(ActionEvent::POST_MODULE_UPDATE, $event);
			$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);
			
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Updates a Action with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// hydrate
		$serializer = Action::getSerializer();
		$action = $serializer->hydrate($action, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($action)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$rows = $action->save();
		$dispatcher->dispatch(ActionEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);

		$payload = ['model' => $action];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates Apis on Action
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateApis($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// remove all relationships before
		ApiQuery::create()->filterByAction($action)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Api';
			}
			$api = ApiQuery::create()->findOneById($entry['id']);
			$action->addApi($api);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_APIS_UPDATE, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$rows = $action->save();
		$dispatcher->dispatch(ActionEvent::POST_APIS_UPDATE, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Updates Groups on Action
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateGroups($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// remove all relationships before
		GroupActionQuery::create()->filterByAction($action)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$action->addGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ActionEvent($action);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ActionEvent::PRE_GROUPS_UPDATE, $event);
		$dispatcher->dispatch(ActionEvent::PRE_SAVE, $event);
		$rows = $action->save();
		$dispatcher->dispatch(ActionEvent::POST_GROUPS_UPDATE, $event);
		$dispatcher->dispatch(ActionEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ActionDomain
	 * 
	 * @param ActionQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(ActionQuery $query, $filter);

	/**
	 * Returns one Action with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Action|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$action = ActionQuery::create()->findOneById($id);
		$this->pool->set($id, $action);

		return $action;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
