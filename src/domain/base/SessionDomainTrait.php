<?php
namespace keeko\core\domain\base;

use keeko\core\model\Session;
use keeko\core\model\SessionQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\utils\Parameters;
use keeko\framework\utils\NameUtils;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Updated;
use keeko\framework\domain\payload\NotUpdated;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\domain\payload\Deleted;
use keeko\framework\domain\payload\NotDeleted;

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
		$session = $serializer->hydrate(new Session(), $data);

		// validate
		if (!$session->validate()) {
			return new NotValid([
				'errors' => $session->getValidationFailures()
			]);
		}

		$session->save();
		return new Created(['model' => $session]);
	}

	/**
	 * Deletes a Session with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$session = $this->get($id);

		if ($session === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		// delete
		$session->delete();

		if ($session->isDeleted()) {
			return new Deleted(['model' => $session]);
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
		$session = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $session]);
	}

	/**
	 * Returns one Session with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$session = $this->get($id);

		// check existence
		if ($session === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		return new Found(['model' => $session]);
	}

	/**
	 * Sets the User id
	 * 
	 * @param mixed $id
	 * @param mixed $userId
	 * @return PayloadInterface
	 */
	public function setUserId($id, $userId) {
		// find
		$session = $this->get($id);

		if ($session === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		// update
		if ($session->getUserId() !== $userId) {
			$session->setUserId($userId);
			$session->save();
			return Updated(['model' => $session]);
		}

		return NotUpdated(['model' => $session]);
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
		$session = $this->get($id);

		if ($session === null) {
			return new NotFound(['message' => 'Session not found.']);
		}

		// hydrate
		$serializer = Session::getSerializer();
		$session = $serializer->hydrate($session, $data);

		// validate
		if (!$session->validate()) {
			return new NotValid([
				'errors' => $session->getValidationFailures()
			]);
		}

		$rows = $session->save();
		$payload = ['model' => $session];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\SessionDomain
	 * 
	 * @param SessionQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(SessionQuery $query, $filter);

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

		$session = SessionQuery::create()->findOneById($id);
		$this->pool->set($id, $session);

		return $session;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
