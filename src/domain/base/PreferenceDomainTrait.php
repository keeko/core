<?php
namespace keeko\core\domain\base;

use keeko\core\model\Preference;
use keeko\core\model\PreferenceQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\core\event\PreferenceEvent;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;

/**
 */
trait PreferenceDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new Preference with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Preference::getSerializer();
		$preference = $serializer->hydrate(new Preference(), $data);

		// dispatch
		$event = new PreferenceEvent($preference);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(PreferenceEvent::PRE_CREATE, $event);
		$dispatcher->dispatch(PreferenceEvent::PRE_SAVE, $event);
		$preference->save();
		$dispatcher->dispatch(PreferenceEvent::POST_CREATE, $event);
		$dispatcher->dispatch(PreferenceEvent::POST_SAVE, $event);
		return new Created(['model' => $preference]);
	}

	/**
	 * Deletes a Preference with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$preference = $this->get($id);

		if ($preference === null) {
			return new NotFound(['message' => 'Preference not found.']);
		}

		// delete
		$event = new PreferenceEvent($preference);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(PreferenceEvent::PRE_DELETE, $event);
		$preference->delete();

		if ($preference->isDeleted()) {
			$dispatcher->dispatch(PreferenceEvent::POST_DELETE, $event);
			return new Deleted(['model' => $preference]);
		}

		return new NotDeleted(['message' => 'Could not delete Preference']);
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

		$query = PreferenceQuery::create();

		// sorting
		$sort = $params->getSort(Preference::getSerializer()->getSortFields());
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
		$preference = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $preference]);
	}

	/**
	 * Returns one Preference with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$preference = $this->get($id);

		// check existence
		if ($preference === null) {
			return new NotFound(['message' => 'Preference not found.']);
		}

		return new Found(['model' => $preference]);
	}

	/**
	 * Updates a Preference with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$preference = $this->get($id);

		if ($preference === null) {
			return new NotFound(['message' => 'Preference not found.']);
		}

		// hydrate
		$serializer = Preference::getSerializer();
		$preference = $serializer->hydrate($preference, $data);

		// dispatch
		$event = new PreferenceEvent($preference);
		$dispatcher = $this->getServiceContainer()->getDispatcher();
		$dispatcher->dispatch(PreferenceEvent::PRE_UPDATE, $event);
		$dispatcher->dispatch(PreferenceEvent::PRE_SAVE, $event);
		$rows = $preference->save();
		$dispatcher->dispatch(PreferenceEvent::POST_UPDATE, $event);
		$dispatcher->dispatch(PreferenceEvent::POST_SAVE, $event);

		$payload = ['model' => $preference];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\PreferenceDomain
	 * 
	 * @param PreferenceQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(PreferenceQuery $query, $filter);

	/**
	 * Returns one Preference with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Preference|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$preference = PreferenceQuery::create()->findOneById($id);
		$this->pool->set($id, $preference);

		return $preference;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
