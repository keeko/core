<?php
namespace keeko\core\domain\base;

use keeko\core\model\Module;
use keeko\core\model\ModuleQuery;
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
trait ModuleDomainTrait {

	/**
	 * Creates a new Module with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = Module::getSerializer();
		$module = $serializer->hydrate(new Module(), $data);

		// validate
		if (!$module->validate()) {
			return new NotValid([
				'errors' => $module->getValidationFailures()
			]);
		}

		$module->save();
		return new Created(['model' => $module]);
	}

	/**
	 * Deletes a Module with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$module = ModuleQuery::create()->findOneById($id);

		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// delete
		$module->delete();

		if ($module->isDeleted()) {
			return new Deleted(['model' => $module]);
		}

		return new NotDeleted(['message' => 'Could not delete Module']);
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

		$query = ModuleQuery::create();

		// sorting
		$sort = $params->getSort(Module::getSerializer()->getSortFields());
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
		$module = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $module]);
	}

	/**
	 * Returns one Module with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$module = ModuleQuery::create()->findOneById($id);

		// check existence
		if ($module === null) {
			$payload = new NotFound(['message' => 'Module not found.']);
		} else {
			$payload = new Found(['model' => $module]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a Module with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$module = ModuleQuery::create()->findOneById($id);

		if ($module === null) {
			return new NotFound(['message' => 'Module not found.']);
		}

		// hydrate
		$serializer = Module::getSerializer();
		$module = $serializer->hydrate($module, $data);

		// validate
		if (!$module->validate()) {
			return new NotValid([
				'errors' => $module->getValidationFailures()
			]);
		}

		$rows = $module->save();
		$payload = ['model' => $module];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ModuleDomain
	 * 
	 * @param ModuleQuery $query
	 */
	abstract protected function applyFilter(ModuleQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
