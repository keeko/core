<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\LanguageTypeQuery;
use keeko\core\domain\base\LanguageTypeDomainTrait;

/**
 */
class LanguageTypeDomain extends AbstractDomain {

	use LanguageTypeDomainTrait;

	/**
	 * @param LanguageTypeQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(LanguageTypeQuery $query, $filter) {
	}
}
