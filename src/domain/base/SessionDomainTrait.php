<?php
namespace keeko\core\domain\base;

use keeko\core\event\SessionEvent;
use keeko\core\model\SessionQuery;
use keeko\core\model\Session;
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
trait SessionDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new Session with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Session::getSerializer();
		$model = $serializer->hydrate(new Session(), $data);
		$this->hydrateRelationships($model, $data);

		// validate
		$validator = $this->getValidator();
		if ($validator !== null && !$validator->validate($model)) {
			return new NotValid([
				'errors' => $validator->getValidationFailures()
			]);
		}

		// dispatch
		$event = new SessionEvent($model);
		$this->dispatch(SessionEvent::PRE_CREATE, $event);
		$this->dispatch(SessionEvent::PRE_SAVE, $event);
		$model->save();
		$this->dispatch(SessionEvent::POST_CREATE, $event);
		$this->dispatch(SessionEvent::POST_SAVE, $event);
		return new Created(['model' => $model]);
	}

	/**
	 * Deletes a Session with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		// delete
		$event = new SessionEvent($model);
		$this->dispatch(SessionEvent::PRE_DELETE, $event);
		$model->delete();

		if ($model->isDeleted()) {
			$this->dispatch(SessionEvent::POST_DELETE, $event);
			return new Deleted(['model' => $model]);
		}

		return new NotDeleted(['message' => 'Could not delete Session']);
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

		$query = SessionQuery::create();

		// sorting
		$sort = $params->getSort(Session::getSerializer()->getSortFields());
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
	 * Returns one Session with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * Sets the User id
	 * 
	 * @param mixed $id
	 * @param mixed $relatedId
	 * @return PayloadInterface
	 */
	public function setUserId($id, $relatedId) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		// update
		if ($this->doSetUserId($model, $relatedId)) {
			$event = new SessionEvent($model);
			$this->dispatch(SessionEvent::PRE_USER_UPDATE, $event);
			$this->dispatch(SessionEvent::PRE_SAVE, $event);
			$model->save();
			$this->dispatch(SessionEvent::POST_USER_UPDATE, $event);
			$this->dispatch(SessionEvent::POST_SAVE, $event);

			return Updated(['model' => $model]);
		}

		return NotUpdated(['model' => $model]);
	}

	/**
	 * Updates a Session with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$model = $this->get($id);

		if ($model === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		// hydrate
		$serializer = Session::getSerializer();
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
		$event = new SessionEvent($model);
		$this->dispatch(SessionEvent::PRE_UPDATE, $event);
		$this->dispatch(SessionEvent::PRE_SAVE, $event);
		$rows = $model->save();
		$this->dispatch(SessionEvent::POST_UPDATE, $event);
		$this->dispatch(SessionEvent::POST_SAVE, $event);

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
	 * @param SessionEvent $event
	 */
	protected function dispatch($type, SessionEvent $event) {
		$model = $event->getSession();
		$methods = [
			SessionEvent::PRE_CREATE => 'preCreate',
			SessionEvent::POST_CREATE => 'postCreate',
			SessionEvent::PRE_UPDATE => 'preUpdate',
			SessionEvent::POST_UPDATE => 'postUpdate',
			SessionEvent::PRE_DELETE => 'preDelete',
			SessionEvent::POST_DELETE => 'postDelete',
			SessionEvent::PRE_SAVE => 'preSave',
			SessionEvent::POST_SAVE => 'postSave'
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
	 * Internal mechanism to set the User id
	 * 
	 * @param Session $model
	 * @param mixed $relatedId
	 */
	protected function doSetUserId(Session $model, $relatedId) {
		if ($model->getUserId() !== $relatedId) {
			$model->setUserId($relatedId);

			return true;
		}

		return false;
	}

	/**
	 * Returns one Session with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Session|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$model = SessionQuery::create()->findOneById($id);
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
