<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\RegionAreaQuery;
use keeko\core\domain\base\RegionAreaDomainTrait;

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
