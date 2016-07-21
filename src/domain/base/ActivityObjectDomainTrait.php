<?php
namespace keeko\core\domain\base;

use keeko\core\event\ActivityObjectEvent;
use keeko\core\model\ActivityObjectQuery;
use keeko\core\model\ActivityObject;
use keeko\core\model\ActivityQuery;
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
trait ActivityObjectDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds Activities to ActivityObject
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addActivities($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddActivities($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_ADD, $model, $data);
		$this->dispatch(ActivityObjectEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActivityObjectEvent::POST_ACTIVITIES_ADD, $model, $data);
		$this->dispatch(ActivityObjectEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Creates a new ActivityObject with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$data = $this->normalize($data);
		$serializer = ActivityObject::getSerializer();
		$model = $serializer->hydrate(new ActivityObject(), $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ActivityObjectEvent::PRE_CREATE, $model, $data);
		$this->dispatch(ActivityObjectEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispatch post save hooks
		$model->save();
		$this->dispatch(ActivityObjectEvent::POST_CREATE, $model, $data);
		$this->dispatch(ActivityObjectEvent::POST_SAVE, $model, $data);

		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a ActivityObject with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// delete
		$this->dispatch(ActivityObjectEvent::PRE_DELETE, $model);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(ActivityObjectEvent::POST_DELETE, $model);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete ActivityObject']);
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

		$query = ActivityObjectQuery::create();

		// sorting
		$sort = $params->getSort(ActivityObject::getSerializer()->getSortFields());
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
	 * Returns one ActivityObject with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * Removes Activities from ActivityObject
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeActivities($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveActivities($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_REMOVE, $model, $data);
		$this->dispatch(ActivityObjectEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActivityObjectEvent::POST_ACTIVITIES_REMOVE, $model, $data);
		$this->dispatch(ActivityObjectEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Updates a ActivityObject with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// hydrate
		$data = $this->normalize($data);
		$serializer = ActivityObject::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ActivityObjectEvent::PRE_UPDATE, $model, $data);
		$this->dispatch(ActivityObjectEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispath post save hooks
		$rows = $model->save();
		$this->dispatch(ActivityObjectEvent::POST_UPDATE, $model, $data);
		$this->dispatch(ActivityObjectEvent::POST_SAVE, $model, $data);

		$payload = ['model' => $model];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates Activities on ActivityObject
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateActivities($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ActivityObject not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateActivities($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ActivityObjectEvent::PRE_ACTIVITIES_UPDATE, $model, $data);
		$this->dispatch(ActivityObjectEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ActivityObjectEvent::POST_ACTIVITIES_UPDATE, $model, $data);
		$this->dispatch(ActivityObjectEvent::POST_SAVE, $model, $data);

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
	 * @param ActivityObject $model
	 * @param array $data
	 */
	protected function dispatch($type, ActivityObject $model, array $data = []) {
		$methods = [
			ActivityObjectEvent::PRE_CREATE => 'preCreate',
			ActivityObjectEvent::POST_CREATE => 'postCreate',
			ActivityObjectEvent::PRE_UPDATE => 'preUpdate',
			ActivityObjectEvent::POST_UPDATE => 'postUpdate',
			ActivityObjectEvent::PRE_DELETE => 'preDelete',
			ActivityObjectEvent::POST_DELETE => 'postDelete',
			ActivityObjectEvent::PRE_SAVE => 'preSave',
			ActivityObjectEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model, $data);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, new ActivityObjectEvent($model));
	}

	/**
	 * Interal mechanism to add Activities to ActivityObject
	 * 
	 * @param ActivityObject $model
	 * @param mixed $data
	 */
	protected function doAddActivities(ActivityObject $model, $data) {
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
	 * Interal mechanism to remove Activities from ActivityObject
	 * 
	 * @param ActivityObject $model
	 * @param mixed $data
	 */
	protected function doRemoveActivities(ActivityObject $model, $data) {
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
	 * Internal update mechanism of Activities on ActivityObject
	 * 
	 * @param ActivityObject $model
	 * @param mixed $data
	 */
	protected function doUpdateActivities(ActivityObject $model, $data) {
		// remove all relationships before
		ActivityQuery::create()->filterByTarget($model)->delete();

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
	 * Returns one ActivityObject with the given id from cache
	 * 
	 * @param mixed $id
	 * @return ActivityObject|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$model = ActivityObjectQuery::create()->findOneById($id);
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
