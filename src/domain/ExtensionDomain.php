<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\ExtensionQuery;
use keeko\core\domain\base\ExtensionDomainTrait;

/**
 */
class ExtensionDomain extends AbstractDomain {

	use ExtensionDomainTrait;

	/**
	 * @param ExtensionQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(ExtensionQuery $query, $filter) {
	}
}
