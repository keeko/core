<?php
namespace keeko\core\domain;

use keeko\core\domain\base\UserDomainTrait;
use keeko\core\model\UserQuery;
use keeko\core\validator\UserValidator;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class UserDomain extends AbstractDomain {

	use UserDomainTrait;

	/**
	 * @param UserQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(UserQuery $query, $filter) {
	}

	/**
	 * Returns the validator for users
	 * 
	 * @return UserValidator
	 */
	protected function getValidator() {
		return new UserValidator($this->getServiceContainer());
	}
}
