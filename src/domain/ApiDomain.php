<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ApiDomainTrait;
use keeko\core\model\ApiQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class ApiDomain extends AbstractDomain {

	use ApiDomainTrait;

	/**
	 * @param ApiQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ApiQuery $query, $filter) {
	}
}
