<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ActivityDomainTrait;
use keeko\core\model\ActivityQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class ActivityDomain extends AbstractDomain {

	use ActivityDomainTrait;

	/**
	 * @param ActivityQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ActivityQuery $query, $filter) {
	}
}
