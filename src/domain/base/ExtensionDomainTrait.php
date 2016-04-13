<?php
namespace keeko\core\domain\base;

use keeko\core\model\Extension;
use keeko\core\model\ExtensionQuery;
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
trait ExtensionDomainTrait {

	/**
	 * Creates a new Extension with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = Extension::getSerializer();
		$extension = $serializer->hydrate(new Extension(), $data);

		// validate
		if (!$extension->validate()) {
			return new NotValid([
				'errors' => $extension->getValidationFailures()
			]);
		}

		$extension->save();
		return new Created(['model' => $extension]);
	}

	/**
	 * Deletes a Extension with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$extension = ExtensionQuery::create()->findOneById($id);

		if ($extension === null) {
			return new NotFound(['message' => 'Extension not found.']);
		}

		// delete
		$extension->delete();

		if ($extension->isDeleted()) {
			return new Deleted(['model' => $extension]);
		}

		return new NotDeleted(['message' => 'Could not delete Extension']);
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

		$query = ExtensionQuery::create();

		// sorting
		$sort = $params->getSort(Extension::getSerializer()->getSortFields());
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
		$extension = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $extension]);
	}

	/**
	 * Returns one Extension with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$extension = ExtensionQuery::create()->findOneById($id);

		// check existence
		if ($extension === null) {
			$payload = new NotFound(['message' => 'Extension not found.']);
		} else {
			$payload = new Found(['model' => $extension]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Extension with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$extension = ExtensionQuery::create()->findOneById($id);

		if ($extension === null) {
			return new NotFound(['message' => 'Extension not found.']);
		}

		// hydrate
		$serializer = Extension::getSerializer();
		$extension = $serializer->hydrate($extension, $data);

		// validate
		if (!$extension->validate()) {
			return new NotValid([
				'errors' => $extension->getValidationFailures()
			]);
		}

		$rows = $extension->save();
		$payload = ['model' => $extension];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ExtensionDomain
	 * 
	 * @param ExtensionQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(ExtensionQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
