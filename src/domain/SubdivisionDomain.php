<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\SubdivisionQuery;
use keeko\core\domain\base\SubdivisionDomainTrait;

/**
 */
class SubdivisionDomain extends AbstractDomain {

	use SubdivisionDomainTrait;

	/**
	 * @param SubdivisionQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(SubdivisionQuery $query, $filter) {
	}
}
