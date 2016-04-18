<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ApplicationUriQuery;
use keeko\core\domain\base\ApplicationUriDomainTrait;

/**
 */
class ApplicationUriDomain extends AbstractDomain {

	use ApplicationUriDomainTrait;

	/**
	 * @param ApplicationUriQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ApplicationUriQuery $query, $filter) {
	}
}
