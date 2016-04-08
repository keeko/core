<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\LocalizationQuery;
use keeko\core\domain\base\LocalizationDomainTrait;

/**
 */
class LocalizationDomain extends AbstractDomain {

	use LocalizationDomainTrait;

	/**
	 * @param LocalizationQuery $query
	 */
	protected function applyFilter(LocalizationQuery $query) {
	}
}
