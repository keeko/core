<?php
namespace keeko\core\domain;

use keeko\core\domain\base\SessionDomainTrait;
use keeko\core\model\SessionQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class SessionDomain extends AbstractDomain {

	use SessionDomainTrait;

	/**
	 * @param SessionQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(SessionQuery $query, $filter) {
	}
}
