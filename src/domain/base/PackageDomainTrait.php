<?php
namespace keeko\core\domain\base;

use keeko\core\model\Package;
use keeko\core\model\PackageQuery;
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
trait PackageDomainTrait {

	/**
	 * Creates a new Package with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function create($data) {
		// hydrate
		$serializer = Package::getSerializer();
		$package = $serializer->hydrate(new Package(), $data);

		// validate
		if (!$package->validate()) {
			return new NotValid([
				'errors' => $package->getValidationFailures()
			]);
		}

		$package->save();
		return new Created(['model' => $package]);
	}

	/**
	 * Deletes a Package with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$package = $this->get($id);

		if ($package === null) {
			return new NotFound(['message' => 'Package not found.']);
		}

		// delete
		$package->delete();

		if ($package->isDeleted()) {
			return new Deleted(['model' => $package]);
		}

		return new NotDeleted(['message' => 'Could not delete Package']);
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

		$query = PackageQuery::create();

		// sorting
		$sort = $params->getSort(Package::getSerializer()->getSortFields());
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
		$package = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $package]);
	}

	/**
	 * Returns one Package with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$package = $this->get($id);

		// check existence
		if ($package === null) {
			return new NotFound(['message' => 'Package not found.']);
		}

		return new Found(['model' => $package]);
	}

	/**
	 * Updates a Package with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$package = $this->get($id);

		if ($package === null) {
			return new NotFound(['message' => 'Package not found.']);
		}

		// hydrate
		$serializer = Package::getSerializer();
		$package = $serializer->hydrate($package, $data);

		// validate
		if (!$package->validate()) {
			return new NotValid([
				'errors' => $package->getValidationFailures()
			]);
		}

		$rows = $package->save();
		$payload = ['model' => $package];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\PackageDomain
	 * 
	 * @param PackageQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(PackageQuery $query, $filter);

	/**
	 * Returns one Package with the given id from cache
	 * 
	 * @param mixed $id
	 * @return Package|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$package = PackageQuery::create()->findOneById($id);
		$this->pool->set($id, $package);

		return $package;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
