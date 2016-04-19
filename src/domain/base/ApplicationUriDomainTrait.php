<?php
namespace keeko\core\domain\base;

use keeko\core\model\ApplicationUri;
use keeko\core\model\ApplicationUriQuery;
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
trait ApplicationUriDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Creates a new ApplicationUri with the provided data
	 * 
	 * @param mixed $data
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$applicationUri = $this->get($id);

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
	 * @return PayloadInterface
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
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$applicationUri = $this->get($id);

		// check existence
		if ($applicationUri === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		return new Found(['model' => $applicationUri]);
	}

	/**
	 * Sets the Application id
	 * 
	 * @param mixed $id
	 * @param mixed $applicationId
	 * @return PayloadInterface
	 */
	public function setApplicationId($id, $applicationId) {
		// find
		$applicationUri = $this->get($id);

		if ($applicationUri === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// update
		if ($applicationUri->getApplicationId() !== $applicationId) {
			$applicationUri->setApplicationId($applicationId);
			$applicationUri->save();
			return Updated(['model' => $applicationUri]);
		}

		return NotUpdated(['model' => $applicationUri]);
	}

	/**
	 * Sets the Localization id
	 * 
	 * @param mixed $id
	 * @param mixed $localizationId
	 * @return PayloadInterface
	 */
	public function setLocalizationId($id, $localizationId) {
		// find
		$applicationUri = $this->get($id);

		if ($applicationUri === null) {
			return new NotFound(['message' => 'ApplicationUri not found.']);
		}

		// update
		if ($applicationUri->getLocalizationId() !== $localizationId) {
			$applicationUri->setLocalizationId($localizationId);
			$applicationUri->save();
			return Updated(['model' => $applicationUri]);
		}

		return NotUpdated(['model' => $applicationUri]);
	}

	/**
	 * Updates a ApplicationUri with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$applicationUri = $this->get($id);

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
	 * @param mixed $filter
	 * @return void
	 */
	abstract protected function applyFilter(ApplicationUriQuery $query, $filter);

	/**
	 * Returns one ApplicationUri with the given id from cache
	 * 
	 * @param mixed $id
	 * @return ApplicationUri|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$applicationUri = ApplicationUriQuery::create()->findOneById($id);
		$this->pool->set($id, $applicationUri);

		return $applicationUri;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
