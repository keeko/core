<?php
namespace keeko\core\domain;

use keeko\framework\foundation\AbstractDomain;
use keeko\core\model\PreferenceQuery;
use keeko\core\domain\base\PreferenceDomainTrait;

/**
 */
class PreferenceDomain extends AbstractDomain {

	use PreferenceDomainTrait;

	/**
	 * @param PreferenceQuery $query
	 * @param mixed $filter
	 */
	protected function applyFilter(PreferenceQuery $query, $filter) {
	}
}
