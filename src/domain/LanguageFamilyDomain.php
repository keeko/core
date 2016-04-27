<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\LanguageFamilyQuery;
use keeko\core\domain\base\LanguageFamilyDomainTrait;

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