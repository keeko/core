<?php
namespace keeko\core\domain;

use keeko\core\domain\base\LanguageFamilyDomainTrait;
use keeko\core\model\LanguageFamilyQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class LanguageFamilyDomain extends AbstractDomain {

	use LanguageFamilyDomainTrait;

	/**
	 * @param LanguageFamilyQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(LanguageFamilyQuery $query, $filter) {
	}
}
