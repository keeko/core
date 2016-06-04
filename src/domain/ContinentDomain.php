<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ContinentDomainTrait;
use keeko\core\model\ContinentQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class ContinentDomain extends AbstractDomain {

	use ContinentDomainTrait;

	/**
	 * @param ContinentQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ContinentQuery $query, $filter) {
	}
}
