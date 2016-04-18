<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ModuleQuery;
use keeko\core\domain\base\ModuleDomainTrait;

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
