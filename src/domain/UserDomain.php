<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\UserQuery;
use keeko\core\domain\base\UserDomainTrait;

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
}
