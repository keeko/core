<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\LanguageVariantQuery;
use keeko\core\domain\base\LanguageVariantDomainTrait;

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
