<?php
namespace keeko\core\domain\base;

use keeko\core\model\RegionArea;
use keeko\core\model\RegionAreaQuery;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\NotFound;
use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\service\ServiceContainer;
use keeko\framework\utils\NameUtils;
use keeko\framework\utils\Parameters;
use phootwork\collection\Map;
use phootwork\lang\Text;

/**
 */
trait RegionAreaDomainTrait {

	/**
	 */
	protected $pool;

	/**
	 * Returns a paginated result
	 * 
	 * @param Parameters $params
	 * @return PayloadInterface
	 */
	public function paginate(Parameters $params) {
		$sysPrefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$defaultSize = $sysPrefs->getPaginationSize();
		$page = $params->getPage('number');
		$size = $params->getPage('size', $defaultSize);

		$query = RegionAreaQuery::create();

		// sorting
		$sort = $params->getSort(RegionArea::getSerializer()->getSortFields());
		foreach ($sort as $field => $order) {
			$method = 'orderBy' . NameUtils::toStudlyCase($field);
			$query->$method($order);
		}

		// filtering
		$filter = $params->getFilter();
		if (!empty($filter)) {
			$this->applyFilter($query, $filter);
		}

		// paginate
		if ($size == -1) {
			$model = $query->findAll();
		} else {
			$model = $query->paginate($page, $size);
		}

		// run response
		return new Found(['model' => $model]);
	}

	/**
	 * Returns one RegionArea with the given id
	 * 
	 * @param mixed $id
	 * @return PayloadInterface
	 */
	public function read($id) {
		// read
		$model = $this->get($id);

		// check existence
		if ($model === null) {
			return new NotFound(['message' => 'RegionArea not found.']);
		}

		return new Found(['model' => $model]);
	}

	/**
	 * @param mixed $query
	 * @param mixed $filter
	 * @return void
	 */
	protected function applyFilter($query, $filter) {
		if (is_array($filter)) {

			// filter by fields
			if (isset($filter['fields'])) {
		    	foreach ($filter['fields'] as $column => $value) {
		        	$pos = strpos($column, '.');
		        	if ($pos !== false) {
		        		$rel = NameUtils::toStudlyCase(substr($column, 0, $pos));
		        		$col = substr($column, $pos + 1);
		        		$method = 'use' . $rel . 'Query';
		        		if (method_exists($query, $method)) {
		        			$sub = $query->$method();
		        			$this->applyFilter($sub, ['fields' => [$col => $value]]);
		        			$sub->endUse();
		        		}
		        	} else {
		        		$method = 'filterBy' . NameUtils::toStudlyCase($column);
		        		if (method_exists($query, $method)) {
		        			$query->$method($value);
		        		}
		        	}
		        }
		    }
		    
		    // filter by features
		    if (isset($filter['features'])) {
		    	$features = new Text($filter['features']);
		    	if ($features->contains('random')) {
		    		$query->addAscendingOrderByColumn('RAND()');
		    	}
		    }
		}

		if (method_exists($this, 'filter')) {
			$this->filter($query, $filter);
		}
	}

	/**
	 * Returns one RegionArea with the given id from cache
	 * 
	 * @param mixed $id
	 * @return RegionArea|null
	 */
	protected function get($id) {
		if ($this->pool === null) {
			$this->pool = new Map();
		} else if ($this->pool->has($id)) {
			return $this->pool->get($id);
		}

		$model = RegionAreaQuery::create()->findOneById($id);
		$this->pool->set($id, $model);

		return $model;
	}

	/**
	 * Returns the service container
	 * 
	 * @return ServiceContainer
	 */
	abstract protected function getServiceContainer();
}
