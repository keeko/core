<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\LanguageScriptQuery;
use keeko\core\domain\base\LanguageScriptDomainTrait;

/**
 */
class LanguageScriptDomain extends AbstractDomain {

	use LanguageScriptDomainTrait;

	/**
	 * @param LanguageScriptQuery $query
	 */
	protected function applyFilter(LanguageScriptQuery $query) {
	}
}
