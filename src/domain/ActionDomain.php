<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ActionQuery;
use keeko\core\domain\base\ActionDomainTrait;

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
