<?php
namespace keeko\core\domain;

use keeko\core\domain\base\LocalizationDomainTrait;
use keeko\core\model\LocalizationQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class LocalizationDomain extends AbstractDomain {

	use LocalizationDomainTrait;

	/**
	 * @param LocalizationQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(LocalizationQuery $query, $filter) {
	}
}
