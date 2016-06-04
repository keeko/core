<?php
namespace keeko\core\domain;

use keeko\core\domain\base\LanguageVariantDomainTrait;
use keeko\core\model\LanguageVariantQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class LanguageVariantDomain extends AbstractDomain {

	use LanguageVariantDomainTrait;

	/**
	 * @param LanguageVariantQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(LanguageVariantQuery $query, $filter) {
	}
}
