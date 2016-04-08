<?php
namespace keeko\core\domain\base;

use keeko\core\model\Application;
use keeko\core\model\ApplicationQuery;
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
trait ApplicationDomainTrait {

	/**
	 * @param mixed $data
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
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$application = ApplicationQuery::create()->findOneById($id);

		if ($application === null) {
			return new NotFound(['message' => 'Application not found.']);
		}

		// delete
		$application->delete();
		$payload = ['model' => $application];

		if ($application->isDeleted()) {
			return new Deleted($payload);
		}

		return new NotDeleted($payload);
	}

	/**
	 * @param Parameters $params
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
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$application = ApplicationQuery::create()->findOneById($id);

		// check existence
		if ($application === null) {
			$payload = new NotFound(['message' => 'Application not found.']);
		} else {
			$payload = new Found(['model' => $application]);
		}

		// run response
		return $payload;
	}

	/**
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$application = ApplicationQuery::create()->findOneById($id);

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
	 */
	abstract protected function applyFilter(ApplicationQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
