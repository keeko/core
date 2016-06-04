<?php
namespace keeko\core\domain;

use keeko\core\domain\base\LanguageTypeDomainTrait;
use keeko\core\model\LanguageTypeQuery;
use keeko\framework\foundation\AbstractDomain;

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
