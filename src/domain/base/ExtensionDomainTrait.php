<?php
namespace keeko\core\domain\base;

use keeko\core\model\Extension;
use keeko\core\model\ExtensionQuery;
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
trait ExtensionDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new Extension with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$extension = $this->get($id);

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
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$extension = $this->get($id);

		// check existence
		if ($extension === null) {
			return new NotFound(['message' => 'Extension not found.']);
		}

		return new Found(['model' => $extension]);
	}

	/**
	 * Sets the Package id
	 * 
	 * @param mixed $id
	 * @param mixed $packageId
	 * @return PayloadInterface
	 */
	public function setPackageId($id, $packageId) {
		// find
		$extension = $this->get($id);

		if ($extension === null) {
			return new NotFound(['message' => 'Extension not found.']);
		}

		// update
		if ($extension->getPackageId() !== $packageId) {
			$extension->setPackageId($packageId);
			$extension->save();
			return Updated(['model' => $extension]);
		}

		return NotUpdated(['model' => $extension]);
	}

	/**
	 * Updates a Extension with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$extension = $this->get($id);

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
	 * @return void
	 */
	abstract protected function applyFilter(ExtensionQuery $query, $filter);

	/**
	 * Returns one Extension with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Extension|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$extension = ExtensionQuery::create()->findOneById($id);
		$this->pool->set($id, $extension);

		return $extension;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
