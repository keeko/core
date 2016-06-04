<?php
namespace keeko\core\domain;

use keeko\core\domain\base\LanguageScopeDomainTrait;
use keeko\core\model\LanguageScopeQuery;
use keeko\framework\foundation\AbstractDomain;

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
