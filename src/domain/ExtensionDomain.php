<?php
namespace keeko\core\domain;

use keeko\core\domain\base\ExtensionDomainTrait;
use keeko\core\model\ExtensionQuery;
use keeko\framework\foundation\AbstractDomain;

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
