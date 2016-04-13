<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\CountryQuery;
use keeko\core\domain\base\CountryDomainTrait;

/**
 */
class CountryDomain extends AbstractDomain {

	use CountryDomainTrait;

	/**
	 * @param CountryQuery $query
	 */
	protected function applyFilter(CountryQuery $query) {
	}
}