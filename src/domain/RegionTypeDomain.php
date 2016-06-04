<?php
namespace keeko\core\domain;

use keeko\core\domain\base\RegionTypeDomainTrait;
use keeko\core\model\RegionTypeQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class RegionTypeDomain extends AbstractDomain {

	use RegionTypeDomainTrait;

	/**
	 * @param RegionTypeQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(RegionTypeQuery $query, $filter) {
	}
}
