<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ApplicationDomainTrait;
use keeko\core\model\ApplicationQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class ApplicationDomain extends AbstractDomain {

	use ApplicationDomainTrait;

	/**
	 * @param ApplicationQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ApplicationQuery $query, $filter) {
	}
}
