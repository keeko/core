<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\LanguageQuery;
use keeko\core\domain\base\LanguageDomainTrait;

/**
 */
class LanguageDomain extends AbstractDomain {

	use LanguageDomainTrait;

	/**
	 * @param LanguageQuery $query
	 */
	protected function applyFilter(LanguageQuery $query) {
	}
}
