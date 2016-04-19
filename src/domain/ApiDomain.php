<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ApiQuery;
use keeko\core\domain\base\ApiDomainTrait;

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
