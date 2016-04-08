<?php
namespace keeko\core\domain\base;

use keeko\core\model\Action;
use keeko\core\model\ActionQuery;
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
trait ActionDomainTrait {

	/**
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = Action::getSerializer();
		$action = $serializer->hydrate(new Action(), $data);

		// validate
		if (!$action->validate()) {
			return new NotValid([
				'errors' => $action->getValidationFailures()
			]);
		}

		$action->save();
		return new Created(['model' => $action]);
	}

	/**
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$action = ActionQuery::create()->findOneById($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// delete
		$action->delete();
		$payload = ['model' => $action];

		if ($action->isDeleted()) {
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

		$query = ActionQuery::create();

		// sorting
		$sort = $params->getSort(Action::getSerializer()->getSortFields());
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
		$action = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $action]);
	}

	/**
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$action = ActionQuery::create()->findOneById($id);

		// check existence
		if ($action === null) {
			$payload = new NotFound(['message' => 'Action not found.']);
		} else {
			$payload = new Found(['model' => $action]);
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
		$action = ActionQuery::create()->findOneById($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// hydrate
		$serializer = Action::getSerializer();
		$action = $serializer->hydrate($action, $data);

		// validate
		if (!$action->validate()) {
			return new NotValid([
				'errors' => $action->getValidationFailures()
			]);
		}

		$rows = $action->save();
		$payload = ['model' => $action];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ActionDomain
	 * 
	 * @param ActionQuery $query
	 */
	abstract protected function applyFilter(ActionQuery $query);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
