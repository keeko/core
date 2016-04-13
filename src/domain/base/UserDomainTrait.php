<?php
namespace keeko\core\domain\base;

use keeko\core\model\User;
use keeko\core\model\UserQuery;
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
trait UserDomainTrait {

	/**
	 * Creates a new User with the provided data
	 * 
	 * @param mixed $data
	 */
	public function create($data) {
		// hydrate
		$serializer = User::getSerializer();
		$user = $serializer->hydrate(new User(), $data);

		// validate
		if (!$user->validate()) {
			return new NotValid([
				'errors' => $user->getValidationFailures()
			]);
		}

		$user->save();
		return new Created(['model' => $user]);
	}

	/**
	 * Deletes a User with the given id
	 * 
	 * @param mixed $id
	 */
	public function delete($id) {
		// find
		$user = UserQuery::create()->findOneById($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// delete
		$user->delete();

		if ($user->isDeleted()) {
			return new Deleted(['model' => $user]);
		}

		return new NotDeleted(['message' => 'Could not delete User']);
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

		$query = UserQuery::create();

		// sorting
		$sort = $params->getSort(User::getSerializer()->getSortFields());
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
		$user = $query->paginate($page, $size);

		// run response
		return new Found(['model' => $user]);
	}

	/**
	 * Returns one User with the given id
	 * 
	 * @param mixed $id
	 */
	public function read($id) {
		// read
		$user = UserQuery::create()->findOneById($id);

		// check existence
		if ($user === null) {
			$payload = new NotFound(['message' => 'User not found.']);
		} else {
			$payload = new Found(['model' => $user]);
		}

		// run response
		return $payload;
	}

	/**
	 * Updates a User with the given idand the provided data
	 * 
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function update($id, $data) {
		// find
		$user = UserQuery::create()->findOneById($id);

		if ($user === null) {
			return new NotFound(['message' => 'User not found.']);
		}

		// hydrate
		$serializer = User::getSerializer();
		$user = $serializer->hydrate($user, $data);

		// validate
		if (!$user->validate()) {
			return new NotValid([
				'errors' => $user->getValidationFailures()
			]);
		}

		$rows = $user->save();
		$payload = ['model' => $user];

		if ($rows === 0) {
			return new NotUpdated($payload);
		}

		return new Updated($payload);
	}

	/**
	 * Implement this functionality at keeko\core\domain\UserDomain
	 * 
	 * @param UserQuery $query
	 * @param mixed $filter
	 */
	abstract protected function applyFilter(UserQuery $query, $filter);

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
