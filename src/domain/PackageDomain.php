<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\PackageQuery;

/**
 */
class PackageDomain extends AbstractDomain {

	use PackageDomainTrait;

	/**
	 * @param PackageQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(PackageQuery $query, $filter) {
	}
}
