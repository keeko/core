<?php
namespace keeko\core\domain;

use keeko\core\domain\base\SubdivisionDomainTrait;
use keeko\core\model\SubdivisionQuery;
use keeko\framework\foundation\AbstractDomain;

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
