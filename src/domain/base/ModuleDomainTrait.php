<?php
namespace keeko\core\domain\base;

use keeko\core\model\Module;
use keeko\core\model\ModuleQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\core\event\ModuleEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;
use keeko\core\model\ActionQuery;

/**
 */
trait ModuleDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Actions to Module
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addActions($id, $data) {
		// find
		$module = $this->get($id);

		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			}
			$action = ActionQuery::create()->findOneById($entry['id']);
			$module->addAction($action);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ModuleEvent($module);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ModuleEvent::PRE_ACTIONS_ADD, $event);
		$dispatcher->dispatch(ModuleEvent::PRE_SAVE, $event);
		$rows = $module->save();
		$dispatcher->dispatch(ModuleEvent::POST_ACTIONS_ADD, $event);
		$dispatcher->dispatch(ModuleEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $module]);
		}

		return NotUpdated(['model' => $module]);
	}

	/**
	 * Creates a new Module with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Module::getSerializer();
		$module = $serializer->hydrate(new Module(), $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($module)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ModuleEvent($module);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ModuleEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(ModuleEvent::PRE_SAVE, $event);
		$module->save();
		$dispatcher->dispatch(ModuleEvent::POST_CREATE, $event);
		$dispatcher->dispatch(ModuleEvent::POST_SAVE, $event);
		return new Created(['model' => $module]);
	}

	/**
	 * Deletes a Module with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$module = $this->get($id);

		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// delete
		$event = new ModuleEvent($module);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ModuleEvent::PRE_DELETE, $event);
		$module->delete();

		if ($module->isDeleted()) {
			$dispatcher->dispatch(ModuleEvent::POST_DELETE, $event);
			return new Deleted(['model' => $module]);
		}

		return new NotDeleted(['message' => 'Could not delete Module']);
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

		$query = ModuleQuery::create();

		// sorting
		$sort = $params->getSort(Module::getSerializer()->getSortFields());
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
		$module = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $module]);
	}

	/**
	 * Returns one Module with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$module = $this->get($id);

		// check existence
		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		return new Found(['model' => $module]);
	}

	/**
	 * Removes Actions from Module
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeActions($id, $data) {
		// find
		$module = $this->get($id);

		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			}
			$action = ActionQuery::create()->findOneById($entry['id']);
			$module->removeAction($action);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ModuleEvent($module);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ModuleEvent::PRE_ACTIONS_REMOVE, $event);
		$dispatcher->dispatch(ModuleEvent::PRE_SAVE, $event);
		$rows = $module->save();
		$dispatcher->dispatch(ModuleEvent::POST_ACTIONS_REMOVE, $event);
		$dispatcher->dispatch(ModuleEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $module]);
		}

		return NotUpdated(['model' => $module]);
	}

	/**
	 * Updates a Module with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$module = $this->get($id);

		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// hydrate
		$serializer = Module::getSerializer();
		$module = $serializer->hydrate($module, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($module)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ModuleEvent($module);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ModuleEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(ModuleEvent::PRE_SAVE, $event);
		$rows = $module->save();
		$dispatcher->dispatch(ModuleEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(ModuleEvent::POST_SAVE, $event);

		$payload = ['model' => $module];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates Actions on Module
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateActions($id, $data) {
		// find
		$module = $this->get($id);

		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// remove all relationships before
		ActionQuery::create()->filterByModule($module)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Action';
			}
			$action = ActionQuery::create()->findOneById($entry['id']);
			$module->addAction($action);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		// save and dispatch events
		$event = new ModuleEvent($module);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ModuleEvent::PRE_ACTIONS_UPDATE, $event);
		$dispatcher->dispatch(ModuleEvent::PRE_SAVE, $event);
		$rows = $module->save();
		$dispatcher->dispatch(ModuleEvent::POST_ACTIONS_UPDATE, $event);
		$dispatcher->dispatch(ModuleEvent::POST_SAVE, $event);

		if ($rows > 0) {
			return Updated(['model' => $module]);
		}

		return NotUpdated(['model' => $module]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ModuleDomain
	 * 
	 * @param ModuleQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(ModuleQuery $query, $filter);

	/**
	 * Returns one Module with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Module|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$module = ModuleQuery::create()->findOneById($id);
		$this->pool->set($id, $module);

		return $module;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
