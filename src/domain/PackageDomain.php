<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\PackageQuery;
use keeko\core\domain\base\PackageDomainTrait;

/**
 */
class PackageDomain extends AbstractDomain {

	use PackageDomainTrait;

	/**
	 * @param PackageQuery $query
	 */
	protected function applyFilter(PackageQuery $query) {
	}
}
