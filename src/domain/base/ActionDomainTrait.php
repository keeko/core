<?php
namespace keeko\core\domain\base;

use keeko\core\model\Action;
use keeko\core\model\ActionQuery;
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
use keeko\core\model\GroupQuery;
use keeko\core\model\GroupActionQuery;

/**
 */
trait ActionDomainTrait {

	/**
	 * Adds Group to Action
	 *
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function addGroup($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}
		 
		// update
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$action->addGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $action->save();

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Creates a new Action with the provided data
	 *
	 * @param mixed $data
	 * @return PayloadInterface
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
	 * Deletes a Action with the given id
	 *
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function delete($id) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// delete
		$action->delete();

		if ($action->isDeleted()) {
			return new Deleted(['model' => $action]);
		}

		return new NotDeleted(['message' => 'Could not delete Action']);
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
	 * Returns one Action with the given id
	 *
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$action = $this->get($id);

		// check existence
		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		return new Found(['model' => $action]);
	}

	/**
	 * Removes Group from Action
	 *
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function removeGroup($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// remove them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$action->removeGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $action->save();

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Sets the Module id
	 *
	 * @param mixed $id
	 * @param mixed $moduleId
	 * @return PayloadInterface
	 */
	public function setModuleId($id, $moduleId) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// update
		if ($action->getModuleId() !== $moduleId) {
			$action->setModuleId($moduleId);
			$action->save();
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Updates a Action with the given idand the provided data
	 *
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function update($id, $data) {
		// find
		$action = $this->get($id);

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
	 * Updates Group on Action
	 *
	 * @param mixed $id
	 * @param mixed $data
	 * @return PayloadInterface
	 */
	public function updateGroup($id, $data) {
		// find
		$action = $this->get($id);

		if ($action === null) {
			return new NotFound(['message' => 'Action not found.']);
		}

		// remove all relationships before
		GroupActionQuery::create()->filterByAction($action)->delete();

		// add them
		$errors = [];
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				$errors[] = 'Missing id for Group';
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$action->addGroup($group);
		}

		if (count($errors) > 0) {
			return new NotValid(['errors' => $errors]);
		}

		$rows = $action->save();

		if ($rows > 0) {
			return Updated(['model' => $action]);
		}

		return NotUpdated(['model' => $action]);
	}

	/**
	 * Implement this functionality at keeko\core\domain\ActionDomain
	 *
	 * @param ActionQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(ActionQuery $query, $filter);

	/**
	 * Returns one Action with the given id from cache
	 *
	 * @param mixed $id
	 * @return Action|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$action = ActionQuery::create()->findOneById($id);
		$this->pool->set($id, $action);

		return $action;
	}

	/**
	 * Returns the service container
	 *
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
