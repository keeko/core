<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\RegionTypeQuery;
use keeko\core\domain\base\RegionTypeDomainTrait;

/**
 */
class RegionTypeDomain extends AbstractDomain {

	use RegionTypeDomainTrait;

	/**
	 * @param RegionTypeQuery $query
	 */
	protected function applyFilter(RegionTypeQuery $query) {
	}
}