<?php
namespace keeko\core\domain\base;

use keeko\core\event\ApplicationUriEvent;
use keeko\core\model\ApplicationUriQuery;
use keeko\core\model\ApplicationUri;
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

/**
 */
trait ApplicationUriDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new ApplicationUri with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = ApplicationUri::getSerializer();
		$model = $serializer->hydrate(new ApplicationUri(), $data);
		$this->hydrateRelationships($model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ApplicationUriEvent($model);
		$this->dispatch(ApplicationUriEvent::PRE_CREATE, $event);
		$this->dispatch(ApplicationUriEvent::PRE_SAVE, $event);
		$model->save();
		$this->dispatch(ApplicationUriEvent::POST_CREATE, $event);
		$this->dispatch(ApplicationUriEvent::POST_SAVE, $event);
		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a ApplicationUri with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// delete
		$event = new ApplicationUriEvent($model);
		$this->dispatch(ApplicationUriEvent::PRE_DELETE, $event);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(ApplicationUriEvent::POST_DELETE, $event);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete ApplicationUri']);
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

		$query = ApplicationUriQuery::create();

		// sorting
		$sort = $params->getSort(ApplicationUri::getSerializer()->getSortFields());
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
	 * Returns one ApplicationUri with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * Sets the Application id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setApplicationId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// update
		if ($this->doSetApplicationId($model, $relatedId)) {
			$event = new ApplicationUriEvent($model);
			$this->dispatch(ApplicationUriEvent::PRE_APPLICATION_UPDATE, $event);
			$this->dispatch(ApplicationUriEvent::PRE_SAVE, $event);
			$model->save();
			$this->dispatch(ApplicationUriEvent::POST_APPLICATION_UPDATE, $event);
			$this->dispatch(ApplicationUriEvent::POST_SAVE, $event);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the Localization id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setLocalizationId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// update
		if ($this->doSetLocalizationId($model, $relatedId)) {
			$event = new ApplicationUriEvent($model);
			$this->dispatch(ApplicationUriEvent::PRE_LOCALIZATION_UPDATE, $event);
			$this->dispatch(ApplicationUriEvent::PRE_SAVE, $event);
			$model->save();
			$this->dispatch(ApplicationUriEvent::POST_LOCALIZATION_UPDATE, $event);
			$this->dispatch(ApplicationUriEvent::POST_SAVE, $event);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Updates a ApplicationUri with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// hydrate
		$serializer = ApplicationUri::getSerializer();
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
		$event = new ApplicationUriEvent($model);
		$this->dispatch(ApplicationUriEvent::PRE_UPDATE, $event);
		$this->dispatch(ApplicationUriEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(ApplicationUriEvent::POST_UPDATE, $event);
		$this->dispatch(ApplicationUriEvent::POST_SAVE, $event);

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
	 * @param ApplicationUriEvent $event
	 */
	protected function dispatch($type, ApplicationUriEvent $event) {
		$model = $event->getApplicationUri();
		$methods = [
			ApplicationUriEvent::PRE_CREATE => 'preCreate',
			ApplicationUriEvent::POST_CREATE => 'postCreate',
			ApplicationUriEvent::PRE_UPDATE => 'preUpdate',
			ApplicationUriEvent::POST_UPDATE => 'postUpdate',
			ApplicationUriEvent::PRE_DELETE => 'preDelete',
			ApplicationUriEvent::POST_DELETE => 'postDelete',
			ApplicationUriEvent::PRE_SAVE => 'preSave',
			ApplicationUriEvent::POST_SAVE => 'postSave'
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
	 * Internal mechanism to set the Application id
	 * 
	 * @param ApplicationUri $model
	 * @param mixed $relatedId
	 */
	protected function doSetApplicationId(ApplicationUri $model, $relatedId) {
		if ($model->getApplicationId() !== $relatedId) {
			$model->setApplicationId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal mechanism to set the Localization id
	 * 
	 * @param ApplicationUri $model
	 * @param mixed $relatedId
	 */
	protected function doSetLocalizationId(ApplicationUri $model, $relatedId) {
		if ($model->getLocalizationId() !== $relatedId) {
			$model->setLocalizationId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Returns one ApplicationUri with the given id from cache
	 * 
	 * @param mixed $id
	 * @return ApplicationUri|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$model = ApplicationUriQuery::create()->findOneById($id);
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
