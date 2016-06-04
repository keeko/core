<?php
namespace keeko\core\domain;

use keeko\core\domain\base\LanguageDomainTrait;
use keeko\core\model\LanguageQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class LanguageDomain extends AbstractDomain {

	use LanguageDomainTrait;

	/**
	 * @param LanguageQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(LanguageQuery $query, $filter) {
	}
}
