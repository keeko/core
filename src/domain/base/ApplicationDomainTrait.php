<?php
namespace keeko\core\domain\base;

use keeko\core\event\ApplicationEvent;
use keeko\core\model\ApplicationQuery;
use keeko\core\model\Application;
use keeko\core\model\ApplicationUriQuery;
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
trait ApplicationDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Adds ApplicationUris to Application
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addApplicationUris($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// pass add to internal logic
		try {
			$this->doAddApplicationUris($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ApplicationEvent::PRE_APPLICATION_URIS_ADD, $model, $data);
		$this->dispatch(ApplicationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ApplicationEvent::POST_APPLICATION_URIS_ADD, $model, $data);
		$this->dispatch(ApplicationEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Creates a new Application with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$data = $this->normalize($data);
		$serializer = Application::getSerializer();
		$model = $serializer->hydrate(new Application(), $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ApplicationEvent::PRE_CREATE, $model, $data);
		$this->dispatch(ApplicationEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispatch post save hooks
		$model->save();
		$this->dispatch(ApplicationEvent::POST_CREATE, $model, $data);
		$this->dispatch(ApplicationEvent::POST_SAVE, $model, $data);

		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Application with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// delete
		$this->dispatch(ApplicationEvent::PRE_DELETE, $model);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(ApplicationEvent::POST_DELETE, $model);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete Application']);
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

		$query = ApplicationQuery::create();

		// sorting
		$sort = $params->getSort(Application::getSerializer()->getSortFields());
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
	 * Returns one Application with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * Removes ApplicationUris from Application
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeApplicationUris($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// pass remove to internal logic
		try {
			$this->doRemoveApplicationUris($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ApplicationEvent::PRE_APPLICATION_URIS_REMOVE, $model, $data);
		$this->dispatch(ApplicationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ApplicationEvent::POST_APPLICATION_URIS_REMOVE, $model, $data);
		$this->dispatch(ApplicationEvent::POST_SAVE, $model, $data);

		if ($rows > 0) {
			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Updates a Application with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// hydrate
		$data = $this->normalize($data);
		$serializer = Application::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ApplicationEvent::PRE_UPDATE, $model, $data);
		$this->dispatch(ApplicationEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispath post save hooks
		$rows = $model->save();
		$this->dispatch(ApplicationEvent::POST_UPDATE, $model, $data);
		$this->dispatch(ApplicationEvent::POST_SAVE, $model, $data);

		$payload = ['model' => $model];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Updates ApplicationUris on Application
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateApplicationUris($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// pass update to internal logic
		try {
			$this->doUpdateApplicationUris($model, $data);
		} catch (ErrorsException $e) {
			return new NotValid(['errors' => $e->getErrors()]);
		}

		// save and dispatch events
		$this->dispatch(ApplicationEvent::PRE_APPLICATION_URIS_UPDATE, $model, $data);
		$this->dispatch(ApplicationEvent::PRE_SAVE, $model, $data);
		$rows = $model->save();
		$this->dispatch(ApplicationEvent::POST_APPLICATION_URIS_UPDATE, $model, $data);
		$this->dispatch(ApplicationEvent::POST_SAVE, $model, $data);

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
	 * @param Application $model
	 * @param array $data
	 */
	protected function dispatch($type, Application $model, array $data = []) {
		$methods = [
			ApplicationEvent::PRE_CREATE => 'preCreate',
			ApplicationEvent::POST_CREATE => 'postCreate',
			ApplicationEvent::PRE_UPDATE => 'preUpdate',
			ApplicationEvent::POST_UPDATE => 'postUpdate',
			ApplicationEvent::PRE_DELETE => 'preDelete',
			ApplicationEvent::POST_DELETE => 'postDelete',
			ApplicationEvent::PRE_SAVE => 'preSave',
			ApplicationEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model, $data);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, new ApplicationEvent($model));
	}

	/**
	 * Interal mechanism to add ApplicationUris to Application
	 * 
	 * @param Application $model
	 * @param mixed $data
	 */
	protected function doAddApplicationUris(Application $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			} else {
				$related = ApplicationUriQuery::create()->findOneById($entry['id']);
				$model->addApplicationUri($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Interal mechanism to remove ApplicationUris from Application
	 * 
	 * @param Application $model
	 * @param mixed $data
	 */
	protected function doRemoveApplicationUris(Application $model, $data) {
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			} else {
				$related = ApplicationUriQuery::create()->findOneById($entry['id']);
				$model->removeApplicationUri($related);
			}
		}

		if (count($errors) > 0) {
			return new ErrorsException($errors);
		}
	}

	/**
	 * Internal update mechanism of ApplicationUris on Application
	 * 
	 * @param Application $model
	 * @param mixed $data
	 */
	protected function doUpdateApplicationUris(Application $model, $data) {
		// remove all relationships before
		ApplicationUriQuery::create()->filterByApplication($model)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for ApplicationUri';
			} else {
				$related = ApplicationUriQuery::create()->findOneById($entry['id']);
				$model->addApplicationUri($related);
			}
		}

		if (count($errors) > 0) {
			throw new ErrorsException($errors);
		}
	}

	/**
	 * Returns one Application with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Application|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$model = ApplicationQuery::create()->findOneById($id);
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
