<?php
namespace keeko\core\domain\base;

use keeko\core\model\Application;
use keeko\core\model\ApplicationQuery;
use keeko\framework\service\ServiceContainer;
use keeko\framework\domain\payload\PayloadInterface;
use phootwork\collection\Map;
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
trait ApplicationDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new Application with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Application::getSerializer();
		$application = $serializer->hydrate(new Application(), $data);

		// validate
		if (!$application->validate()) {
			return new NotValid([
				'errors' => $application->getValidationFailures()
			]);
		}

		$application->save();
		return new Created(['model' => $application]);
	}

	/**
	 * Deletes a Application with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$application = $this->get($id);

		if ($application === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// delete
		$application->delete();

		if ($application->isDeleted()) {
			return new Deleted(['model' => $application]);
		}

		return new NotDeleted(['message' => 'Could not delete Application']);
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
		$application = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $application]);
	}

	/**
	 * Returns one Application with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$application = $this->get($id);

		// check existence
		if ($application === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		return new Found(['model' => $application]);
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
		$application = $this->get($id);

		if ($application === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// hydrate
		$serializer = Application::getSerializer();
		$application = $serializer->hydrate($application, $data);

		// validate
		if (!$application->validate()) {
			return new NotValid([
				'errors' => $application->getValidationFailures()
			]);
		}

		$rows = $application->save();
		$payload = ['model' => $application];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ApplicationDomain
	 * 
	 * @param ApplicationQuery $query
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(ApplicationQuery $query, $filter);

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

		$application = ApplicationQuery::create()->findOneById($id);
		$this->pool->set($id, $application);

		return $application;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
