<?php
namespace keeko\core\domain\base;

use keeko\core\event\ActivityEvent;
use keeko\core\model\ActivityQuery;
use keeko\core\model\Activity;
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
trait ActivityDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new Activity with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Activity::getSerializer();
		$model = $serializer->hydrate(new Activity(), $data);
		$this->hydrateRelationships($model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new ActivityEvent($model);
		$this->dispatch(ActivityEvent::PRE_CREATE, $event);
		$this->dispatch(ActivityEvent::PRE_SAVE, $event);
		$model->save();
		$this->dispatch(ActivityEvent::POST_CREATE, $event);
		$this->dispatch(ActivityEvent::POST_SAVE, $event);
		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Activity with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// delete
		$event = new ActivityEvent($model);
		$this->dispatch(ActivityEvent::PRE_DELETE, $event);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(ActivityEvent::POST_DELETE, $event);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete Activity']);
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

		$query = ActivityQuery::create();

		// sorting
		$sort = $params->getSort(Activity::getSerializer()->getSortFields());
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
	 * Returns one Activity with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * Sets the Actor id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setActorId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// update
		if ($this->doSetActorId($model, $relatedId)) {
			$event = new ActivityEvent($model);
			$this->dispatch(ActivityEvent::PRE_ACTOR_UPDATE, $event);
			$this->dispatch(ActivityEvent::PRE_SAVE, $event);
			$model->save();
			$this->dispatch(ActivityEvent::POST_ACTOR_UPDATE, $event);
			$this->dispatch(ActivityEvent::POST_SAVE, $event);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the Object id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setObjectId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// update
		if ($this->doSetObjectId($model, $relatedId)) {
			$event = new ActivityEvent($model);
			$this->dispatch(ActivityEvent::PRE_OBJECT_UPDATE, $event);
			$this->dispatch(ActivityEvent::PRE_SAVE, $event);
			$model->save();
			$this->dispatch(ActivityEvent::POST_OBJECT_UPDATE, $event);
			$this->dispatch(ActivityEvent::POST_SAVE, $event);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Sets the Target id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setTargetId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// update
		if ($this->doSetTargetId($model, $relatedId)) {
			$event = new ActivityEvent($model);
			$this->dispatch(ActivityEvent::PRE_TARGET_UPDATE, $event);
			$this->dispatch(ActivityEvent::PRE_SAVE, $event);
			$model->save();
			$this->dispatch(ActivityEvent::POST_TARGET_UPDATE, $event);
			$this->dispatch(ActivityEvent::POST_SAVE, $event);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Updates a Activity with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Activity not found.']);
		}

		// hydrate
		$serializer = Activity::getSerializer();
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
		$event = new ActivityEvent($model);
		$this->dispatch(ActivityEvent::PRE_UPDATE, $event);
		$this->dispatch(ActivityEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(ActivityEvent::POST_UPDATE, $event);
		$this->dispatch(ActivityEvent::POST_SAVE, $event);

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
	 * @param ActivityEvent $event
	 */
	protected function dispatch($type, ActivityEvent $event) {
		$model = $event->getActivity();
		$methods = [
			ActivityEvent::PRE_CREATE => 'preCreate',
			ActivityEvent::POST_CREATE => 'postCreate',
			ActivityEvent::PRE_UPDATE => 'preUpdate',
			ActivityEvent::POST_UPDATE => 'postUpdate',
			ActivityEvent::PRE_DELETE => 'preDelete',
			ActivityEvent::POST_DELETE => 'postDelete',
			ActivityEvent::PRE_SAVE => 'preSave',
			ActivityEvent::POST_SAVE => 'postSave'
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
	 * Internal mechanism to set the Actor id
	 * 
	 * @param Activity $model
	 * @param mixed $relatedId
	 */
	protected function doSetActorId(Activity $model, $relatedId) {
		if ($model->getActorId() !== $relatedId) {
			$model->setActorId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal mechanism to set the Object id
	 * 
	 * @param Activity $model
	 * @param mixed $relatedId
	 */
	protected function doSetObjectId(Activity $model, $relatedId) {
		if ($model->getObjectId() !== $relatedId) {
			$model->setObjectId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Internal mechanism to set the Target id
	 * 
	 * @param Activity $model
	 * @param mixed $relatedId
	 */
	protected function doSetTargetId(Activity $model, $relatedId) {
		if ($model->getTargetId() !== $relatedId) {
			$model->setTargetId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Returns one Activity with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Activity|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$model = ActivityQuery::create()->findOneById($id);
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
