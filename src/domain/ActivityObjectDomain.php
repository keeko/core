<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ActivityObjectDomainTrait;
use keeko\core\model\ActivityObjectQuery;
use keeko\framework\foundation\AbstractDomain;

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
