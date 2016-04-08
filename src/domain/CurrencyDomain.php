<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\CurrencyQuery;
use keeko\core\domain\base\CurrencyDomainTrait;

/**
 */
class CurrencyDomain extends AbstractDomain {

	use CurrencyDomainTrait;

	/**
	 * @param CurrencyQuery $query
	 */
	protected function applyFilter(CurrencyQuery $query) {
	}
}
