<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\GroupQuery;
use keeko\core\domain\base\GroupDomainTrait;

/**
 */
class GroupDomain extends AbstractDomain {

	use GroupDomainTrait;

	/**
	 * @param GroupQuery $query
	 */
	protected function applyFilter(GroupQuery $query) {
	}
}
