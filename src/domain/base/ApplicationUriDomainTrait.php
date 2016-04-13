<?php
namespace keeko\core\domain\base;

use keeko\core\model\ApplicationUri;
use keeko\core\model\ApplicationUriQuery;
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
trait ApplicationUriDomainTrait {

	/**
	 * Creates a new ApplicationUri with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = ApplicationUri::getSerializer();
		$applicationUri = $serializer->hydrate(new ApplicationUri(), $data);

		// validate
		if (!$applicationUri->validate()) {
			return new NotValid([
				'errors' => $applicationUri->getValidationFailures()
			]);
		}

		$applicationUri->save();
		return new Created(['model' => $applicationUri]);
	}

	/**
	 * Deletes a ApplicationUri with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$applicationUri = ApplicationUriQuery::create()->findOneById($id);

		if ($applicationUri === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// delete
		$applicationUri->delete();

		if ($applicationUri->isDeleted()) {
			return new Deleted(['model' => $applicationUri]);
		}

		return new NotDeleted(['message' => 'Could not delete ApplicationUri']);
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
		$applicationUri = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $applicationUri]);
	}

	/**
	 * Returns one ApplicationUri with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$applicationUri = ApplicationUriQuery::create()->findOneById($id);

		// check existence
		if ($applicationUri === null) {
			$payload = new NotFound(['message' => 'ApplicationUri not found.']);
		} else {
			$payload = new Found(['model' => $applicationUri]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a ApplicationUri with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$applicationUri = ApplicationUriQuery::create()->findOneById($id);

		if ($applicationUri === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// hydrate
		$serializer = ApplicationUri::getSerializer();
		$applicationUri = $serializer->hydrate($applicationUri, $data);

		// validate
		if (!$applicationUri->validate()) {
			return new NotValid([
				'errors' => $applicationUri->getValidationFailures()
			]);
		}

		$rows = $applicationUri->save();
		$payload = ['model' => $applicationUri];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ApplicationUriDomain
	 * 
	 * @param ApplicationUriQuery $query
	 */
	abstract protected function applyFilter(ApplicationUriQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
