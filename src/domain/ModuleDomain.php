<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ModuleDomainTrait;
use keeko\core\model\ModuleQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class ModuleDomain extends AbstractDomain {

	use ModuleDomainTrait;

	/**
	 * @param ModuleQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ModuleQuery $query, $filter) {
	}
}
