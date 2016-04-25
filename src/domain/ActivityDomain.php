<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ActivityQuery;
use keeko\core\domain\base\ActivityDomainTrait;

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
