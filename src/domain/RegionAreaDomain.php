<?php
namespace keeko\core\domain;

use keeko\core\domain\base\RegionAreaDomainTrait;
use keeko\core\model\RegionAreaQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class RegionAreaDomain extends AbstractDomain {

	use RegionAreaDomainTrait;

	/**
	 * @param RegionAreaQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(RegionAreaQuery $query, $filter) {
	}
}
