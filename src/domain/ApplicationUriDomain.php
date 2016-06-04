<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ApplicationUriDomainTrait;
use keeko\core\model\ApplicationUriQuery;
use keeko\framework\foundation\AbstractDomain;

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
