<?php
namespace keeko\core\domain;

use keeko\core\domain\base\LanguageScriptDomainTrait;
use keeko\core\model\LanguageScriptQuery;
use keeko\framework\foundation\AbstractDomain;

/**
 */
class LanguageScriptDomain extends AbstractDomain {

	use LanguageScriptDomainTrait;

	/**
	 * @param LanguageScriptQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(LanguageScriptQuery $query, $filter) {
	}
}
