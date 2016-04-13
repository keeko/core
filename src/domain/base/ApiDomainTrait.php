<?php
namespace keeko\core\domain\base;

use keeko\core\model\Api;
use keeko\core\model\ApiQuery;
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
trait ApiDomainTrait {

	/**
	 * Creates a new Api with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = Api::getSerializer();
		$api = $serializer->hydrate(new Api(), $data);

		// validate
		if (!$api->validate()) {
			return new NotValid([
				'errors' => $api->getValidationFailures()
			]);
		}

		$api->save();
		return new Created(['model' => $api]);
	}

	/**
	 * Deletes a Api with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$api = ApiQuery::create()->findOneById($id);

		if ($api === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// delete
		$api->delete();

		if ($api->isDeleted()) {
			return new Deleted(['model' => $api]);
		}

		return new NotDeleted(['message' => 'Could not delete Api']);
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
	 */
	public function read($id) {
		// read
		$api = ApiQuery::create()->findOneById($id);

		// check existence
		if ($api === null) {
			$payload = new NotFound(['message' => 'Api not found.']);
		} else {
			$payload = new Found(['model' => $api]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Api with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$api = ApiQuery::create()->findOneById($id);

		if ($api === null) {
			return new NotFound(['message' => 'Api not found.']);
		}

		// hydrate
		$serializer = Api::getSerializer();
		$api = $serializer->hydrate($api, $data);

		// validate
		if (!$api->validate()) {
			return new NotValid([
				'errors' => $api->getValidationFailures()
			]);
		}

		$rows = $api->save();
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
	 */
	abstract protected function applyFilter(ApiQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
