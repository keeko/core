<?php
namespace keeko\core\domain;

use keeko\core\domain\base\CountryDomainTrait;
use keeko\core\model\CountryQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class CountryDomain extends AbstractDomain {

	use CountryDomainTrait;

	/**
	 * @param CountryQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(CountryQuery $query, $filter) {
	}
}
