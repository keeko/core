<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ActivityObjectQuery;
use keeko\core\domain\base\ActivityObjectDomainTrait;

/**
 */
class ActivityObjectDomain extends AbstractDomain {

	use ActivityObjectDomainTrait;

	/**
	 * @param ActivityObjectQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ActivityObjectQuery $query, $filter) {
	}
}
