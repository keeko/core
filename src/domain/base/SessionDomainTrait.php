<?php
namespace keeko\core\domain\base;

use keeko\core\model\Session;
use keeko\core\model\SessionQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use Tobscure\JsonApi\Parameters;
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
	 * Creates a new Session with the provided data
	 * 
	 * @param mixed $data
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
	 */
	public function delete($id) {
		// find
		$session = SessionQuery::create()->findOneById($id);

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
	 */
	public function read($id) {
		// read
		$session = SessionQuery::create()->findOneById($id);

		// check existence
		if ($session === null) {
			$payload = new NotFound(['message' => 'Session not found.']);
		} else {
			$payload = new Found(['model' => $session]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Session with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$session = SessionQuery::create()->findOneById($id);

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
	 */
	abstract protected function applyFilter(SessionQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
