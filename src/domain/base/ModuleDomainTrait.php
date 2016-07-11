<?php
namespace keeko\core\domain\base;

use keeko\core\event\ModuleEvent;
use keeko\core\model\ActionQuery;
use keeko\core\model\ModuleQuery;
use keeko\core\model\Module;
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddActions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ModuleEvent::PRE_ACTIONS_ADD, $model, $data);
		$this->dispatch(ModuleEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ModuleEvent::POST_ACTIONS_ADD, $model, $data);
		$this->dispatch(ModuleEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $serializer->hydrate(new Module(), $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ModuleEvent::PRE_CREATE, $model, $data);
		$this->dispatch(ModuleEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispatch post save hooks
		$model->save();
		$this->dispatch(ModuleEvent::POST_CREATE, $model, $data);
		$this->dispatch(ModuleEvent::POST_SAVE, $model, $data);

		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Module with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// delete
		$this->dispatch(ModuleEvent::PRE_DELETE, $model);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(ModuleEvent::POST_DELETE, $model);
			return new Deleted(['model' => $model]);
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
		$model = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $model]);
	}

	/**
	 * Returns one Module with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		return new Found(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveActions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ModuleEvent::PRE_ACTIONS_REMOVE, $model, $data);
		$this->dispatch(ModuleEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ModuleEvent::POST_ACTIONS_REMOVE, $model, $data);
		$this->dispatch(ModuleEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// hydrate
		$serializer = Module::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ModuleEvent::PRE_UPDATE, $model, $data);
		$this->dispatch(ModuleEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispath post save hooks
		$rows = $model->save();
		$this->dispatch(ModuleEvent::POST_UPDATE, $model, $data);
		$this->dispatch(ModuleEvent::POST_SAVE, $model, $data);

		$payload = ['model' => $model];

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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateActions($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ModuleEvent::PRE_ACTIONS_UPDATE, $model, $data);
		$this->dispatch(ModuleEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ModuleEvent::POST_ACTIONS_UPDATE, $model, $data);
		$this->dispatch(ModuleEvent::POST_SAVE, $model, $data);

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
	 * @param Module $model
	 * @param array $data
	 */
	protected function dispatch($type, Module $model, array $data = []) {
		$methods = [
			ModuleEvent::PRE_CREATE => 'preCreate',
			ModuleEvent::POST_CREATE => 'postCreate',
			ModuleEvent::PRE_UPDATE => 'preUpdate',
			ModuleEvent::POST_UPDATE => 'postUpdate',
			ModuleEvent::PRE_DELETE => 'preDelete',
			ModuleEvent::POST_DELETE => 'postDelete',
			ModuleEvent::PRE_SAVE => 'preSave',
			ModuleEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model, $data);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, new ModuleEvent($model));
	}

	/**
	 * Interal mechanism to add Actions to Module
	 * 
	 * @param Module $model
	 * @param mixed $data
	 */
	protected function doAddActions(Module $model, $data) {
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
	 * Interal mechanism to remove Actions from Module
	 * 
	 * @param Module $model
	 * @param mixed $data
	 */
	protected function doRemoveActions(Module $model, $data) {
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
	 * Internal update mechanism of Actions on Module
	 * 
	 * @param Module $model
	 * @param mixed $data
	 */
	protected function doUpdateActions(Module $model, $data) {
		// remove all relationships before
		ActionQuery::create()->filterByModule($model)->delete();

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

		$model = ModuleQuery::create()->findOneById($id);
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
