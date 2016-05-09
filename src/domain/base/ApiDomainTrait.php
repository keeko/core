<?php
namespace keeko\core\domain\base;

use keeko\core\model\Api;
use keeko\core\model\ApiQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\core\event\ApiEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;

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
		$serializer = Api::getSerializer();
		$api = $serializer->hydrate(new Api(), $data);

		// dispatch
		$event = new ApiEvent($api);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ApiEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(ApiEvent::PRE_SAVE, $event);
		$api->save();
		$dispatcher->dispatch(ApiEvent::POST_CREATE, $event);
		$dispatcher->dispatch(ApiEvent::POST_SAVE, $event);
		return new Created(['model' => $api]);
	}

	/**
	 * Deletes a Api with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$api = $this->get($id);

		if ($api === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// delete
		$event = new ApiEvent($api);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ApiEvent::PRE_DELETE, $event);
		$api->delete();

		if ($api->isDeleted()) {
			$dispatcher->dispatch(ApiEvent::POST_DELETE, $event);
			return new Deleted(['model' => $api]);
		}

		return new NotDeleted(['message' => 'Could not delete Api']);
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
		$api = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $api]);
	}

	/**
	 * Returns one Api with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$api = $this->get($id);

		// check existence
		if ($api === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		return new Found(['model' => $api]);
	}

	/**
	 * Sets the Action id
	 * 
	 * @param mixed $id
	 * @param mixed $actionId
	 * @return PayloadInterface
	 */
	public function setActionId($id, $actionId) {
		// find
		$api = $this->get($id);

		if ($api === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// update
		if ($api->getActionId() !== $actionId) {
			$api->setActionId($actionId);

			$event = new ApiEvent($api);
			$dispatcher = $this->getServiceContainer()->getDispatcher();
			$dispatcher->dispatch(ApiEvent::PRE_ACTION_UPDATE, $event);
			$dispatcher->dispatch(ApiEvent::PRE_SAVE, $event);
			$api->save();
			$dispatcher->dispatch(ApiEvent::POST_ACTION_UPDATE, $event);
			$dispatcher->dispatch(ApiEvent::POST_SAVE, $event);
			
			return Updated(['model' => $api]);
		}

		return NotUpdated(['model' => $api]);
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
		$api = $this->get($id);

		if ($api === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// hydrate
		$serializer = Api::getSerializer();
		$api = $serializer->hydrate($api, $data);

		// dispatch
		$event = new ApiEvent($api);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(ApiEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(ApiEvent::PRE_SAVE, $event);
		$rows = $api->save();
		$dispatcher->dispatch(ApiEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(ApiEvent::POST_SAVE, $event);

		$payload = ['model' => $api];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ApiDomain
	 * 
	 * @param ApiQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(ApiQuery $query, $filter);

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

		$api = ApiQuery::create()->findOneById($id);
		$this->pool->set($id, $api);

		return $api;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
