<?php
namespace keeko\core\domain;

use keeko\core\domain\base\CurrencyDomainTrait;
use keeko\core\model\CurrencyQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class CurrencyDomain extends AbstractDomain {

	use CurrencyDomainTrait;

	/**
	 * @param CurrencyQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(CurrencyQuery $query, $filter) {
	}
}
