<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ContinentQuery;
use keeko\core\domain\base\ContinentDomainTrait;

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
