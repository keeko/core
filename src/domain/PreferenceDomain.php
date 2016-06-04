<?php
namespace keeko\core\domain;

use keeko\core\domain\base\PreferenceDomainTrait;
use keeko\core\model\PreferenceQuery;
use keeko\framework\foundation\AbstractDomain;

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
