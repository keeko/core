<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\SessionQuery;
use keeko\core\domain\base\SessionDomainTrait;

/**
 */
class SessionDomain extends AbstractDomain {

	use SessionDomainTrait;

	/**
	 * @param SessionQuery $query
	 */
	protected function applyFilter(SessionQuery $query) {
	}
}
