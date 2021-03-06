<?php
namespace keeko\core\domain\base;

use keeko\core\event\ApiEvent;
use keeko\core\model\Api;
use keeko\core\model\ApiQuery;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotDeleted;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\domain\payload\Updated;
use keeko\framework\service\ServiceContainer;
use keeko\framework\utils\NameUtils;
use keeko\framework\utils\Parameters;
use phootwork\collection\Map;
use phootwork\lang\Text;

/**
 */
trait ApiDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new Api with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$data = $this->normalize($data);
		$serializer = Api::getSerializer();
		$model = $serializer->hydrate(new Api(), $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ApiEvent::PRE_CREATE, $model, $data);
		$this->dispatch(ApiEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispatch post save hooks
		$model->save();
		$this->dispatch(ApiEvent::POST_CREATE, $model, $data);
		$this->dispatch(ApiEvent::POST_SAVE, $model, $data);

		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Api with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// delete
		$this->dispatch(ApiEvent::PRE_DELETE, $model);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(ApiEvent::POST_DELETE, $model);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete Api']);
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

		$query = ApiQuery::create();

		// sorting
		$sort = $params->getSort(Api::getSerializer()->getSortFields());
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
	 * Returns one Api with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * Sets the Action id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setActionId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// update
		if ($this->doSetActionId($model, $relatedId)) {
			$this->dispatch(ApiEvent::PRE_ACTION_UPDATE, $model);
			$this->dispatch(ApiEvent::PRE_SAVE, $model);
			$model->save();
			$this->dispatch(ApiEvent::POST_ACTION_UPDATE, $model);
			$this->dispatch(ApiEvent::POST_SAVE, $model);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Updates a Api with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// hydrate
		$data = $this->normalize($data);
		$serializer = Api::getSerializer();
		$model = $serializer->hydrate($model, $data);
		$this->hydrateRelationships($model, $data);

		// dispatch pre save hooks
		$this->dispatch(ApiEvent::PRE_UPDATE, $model, $data);
		$this->dispatch(ApiEvent::PRE_SAVE, $model, $data);

		// validate
		$validator = $this->getValidator($model);
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// save and dispath post save hooks
		$rows = $model->save();
		$this->dispatch(ApiEvent::POST_UPDATE, $model, $data);
		$this->dispatch(ApiEvent::POST_SAVE, $model, $data);

		$payload = ['model' => $model];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
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
	 * @param Api $model
	 * @param array $data
	 */
	protected function dispatch($type, Api $model, array $data = []) {
		$methods = [
			ApiEvent::PRE_CREATE => 'preCreate',
			ApiEvent::POST_CREATE => 'postCreate',
			ApiEvent::PRE_UPDATE => 'preUpdate',
			ApiEvent::POST_UPDATE => 'postUpdate',
			ApiEvent::PRE_DELETE => 'preDelete',
			ApiEvent::POST_DELETE => 'postDelete',
			ApiEvent::PRE_SAVE => 'preSave',
			ApiEvent::POST_SAVE => 'postSave'
		];

		if (isset($methods[$type])) {
			$method = $methods[$type];
			if (method_exists($this, $method)) {
				$this->$method($model, $data);
			}
		}

		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch($type, new ApiEvent($model));
	}

	/**
	 * Internal mechanism to set the Action id
	 * 
	 * @param Api $model
	 * @param mixed $relatedId
	 */
	protected function doSetActionId(Api $model, $relatedId) {
		if ($model->getActionId() !== $relatedId) {
			$model->setActionId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Returns one Api with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Api|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$model = ApiQuery::create()->findOneById($id);
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
