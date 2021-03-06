<?php
namespace keeko\core\domain\base;

use keeko\core\event\ActionEvent;
use keeko\core\model\ActionQuery;
use keeko\core\model\Action;
use keeko\core\model\ApiQuery;
use keeko\core\model\GroupActionQuery;
use keeko\core\model\GroupQuery;
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddApis($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActionEvent::PRE_APIS_ADD, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActionEvent::POST_APIS_ADD, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddGroups($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActionEvent::PRE_GROUPS_ADD, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActionEvent::POST_GROUPS_ADD, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Creates a new Action with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$data = $this->normalize($data);
		$serializer = Action::getSerializer();
		$model = $serializer->hydrate(new Action(), $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ActionEvent::PRE_CREATE, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispatch post save hooks
		$model->save();
		$this->dispatch(ActionEvent::POST_CREATE, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Action with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// delete
		$this->dispatch(ActionEvent::PRE_DELETE, $model);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(ActionEvent::POST_DELETE, $model);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete Action']);
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
		if ($size == -1) {
			$model = $query->findAll();
		} else {
			$model = $query->paginate($page, $size);
		}

		// run response
		return new Found(['model' => $model]);
	}

	/**
	 * Returns one Action with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		return new Found(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveApis($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActionEvent::PRE_APIS_REMOVE, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActionEvent::POST_APIS_REMOVE, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveGroups($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActionEvent::PRE_GROUPS_REMOVE, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActionEvent::POST_GROUPS_REMOVE, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the Module id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setModuleId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// update
		if ($this->doSetModuleId($model, $relatedId)) {
			$this->dispatch(ActionEvent::PRE_MODULE_UPDATE, $model);
			$this->dispatch(ActionEvent::PRE_SAVE, $model);
			$model->save();
			$this->dispatch(ActionEvent::POST_MODULE_UPDATE, $model);
			$this->dispatch(ActionEvent::POST_SAVE, $model);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// hydrate
		$data = $this->normalize($data);
		$serializer = Action::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ActionEvent::PRE_UPDATE, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispath post save hooks
		$rows = $model->save();
		$this->dispatch(ActionEvent::POST_UPDATE, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

		$payload = ['model' => $model];

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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateApis($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActionEvent::PRE_APIS_UPDATE, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActionEvent::POST_APIS_UPDATE, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
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
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateGroups($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActionEvent::PRE_GROUPS_UPDATE, $model, $data);
		$this->dispatch(ActionEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActionEvent::POST_GROUPS_UPDATE, $model, $data);
		$this->dispatch(ActionEvent::POST_SAVE, $model, $data);

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
	 * @param Action $model
	 * @param array $data
	 */
	protected function dispatch($type, Action $model, array $data = []) {
		$methods = [
			ActionEvent::PRE_CREATE => 'preCreate',
			ActionEvent::POST_CREATE => 'postCreate',
			ActionEvent::PRE_UPDATE => 'preUpdate',
			ActionEvent::POST_UPDATE => 'postUpdate',
			ActionEvent::PRE_DELETE => 'preDelete',
			ActionEvent::POST_DELETE => 'postDelete',
			ActionEvent::PRE_SAVE => 'preSave',
			ActionEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model, $data);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, new ActionEvent($model));
	}

	/**
	 * Interal mechanism to add Apis to Action
	 * 
	 * @param Action $model
	 * @param mixed $data
	 */
	protected function doAddApis(Action $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Api';
			} else {
				$related = ApiQuery::create()->findOneById($entry['id']);
				$model->addApi($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to add Groups to Action
	 * 
	 * @param Action $model
	 * @param mixed $data
	 */
	protected function doAddGroups(Action $model, $data) {
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
	 * Interal mechanism to remove Apis from Action
	 * 
	 * @param Action $model
	 * @param mixed $data
	 */
	protected function doRemoveApis(Action $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Api';
			} else {
				$related = ApiQuery::create()->findOneById($entry['id']);
				$model->removeApi($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove Groups from Action
	 * 
	 * @param Action $model
	 * @param mixed $data
	 */
	protected function doRemoveGroups(Action $model, $data) {
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
	 * Internal mechanism to set the Module id
	 * 
	 * @param Action $model
	 * @param mixed $relatedId
	 */
	protected function doSetModuleId(Action $model, $relatedId) {
		if ($model->getModuleId() !== $relatedId) {
			$model->setModuleId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal update mechanism of Apis on Action
	 * 
	 * @param Action $model
	 * @param mixed $data
	 */
	protected function doUpdateApis(Action $model, $data) {
		// remove all relationships before
		ApiQuery::create()->filterByAction($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Api';
			} else {
				$related = ApiQuery::create()->findOneById($entry['id']);
				$model->addApi($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of Groups on Action
	 * 
	 * @param Action $model
	 * @param mixed $data
	 */
	protected function doUpdateGroups(Action $model, $data) {
		// remove all relationships before
		GroupActionQuery::create()->filterByAction($model)->delete();

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

		$model = ActionQuery::create()->findOneById($id);
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
