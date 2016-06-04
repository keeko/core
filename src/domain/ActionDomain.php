<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ActionDomainTrait;
use keeko\core\model\ActionQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class ActionDomain extends AbstractDomain {

	use ActionDomainTrait;

	/**
	 * @param ActionQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ActionQuery $query, $filter) {
	}
}
