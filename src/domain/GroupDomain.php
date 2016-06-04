<?php
namespace keeko\core\domain;

use keeko\core\domain\base\GroupDomainTrait;
use keeko\core\model\GroupQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class GroupDomain extends AbstractDomain {

	use GroupDomainTrait;

	/**
	 * @param GroupQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(GroupQuery $query, $filter) {
	}
}
