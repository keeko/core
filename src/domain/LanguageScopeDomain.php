<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\LanguageScopeQuery;
use keeko\core\domain\base\LanguageScopeDomainTrait;

/**
 */
class LanguageScopeDomain extends AbstractDomain {

	use LanguageScopeDomainTrait;

	/**
	 * @param LanguageScopeQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(LanguageScopeQuery $query, $filter) {
	}
}
