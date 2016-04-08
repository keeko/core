<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ApplicationQuery;
use keeko\core\domain\base\ApplicationDomainTrait;

/**
 */
class ApplicationDomain extends AbstractDomain {

	use ApplicationDomainTrait;

	/**
	 * @param ApplicationQuery $query
	 */
	protected function applyFilter(ApplicationQuery $query) {
	}
}
