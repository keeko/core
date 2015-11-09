<?php
namespace keeko\core\schema;

use phootwork\collection\Map;
use phootwork\collection\Set;

class ModuleSchema extends KeekoPackageSchema {
	
	/** @var Map<string, ActionSchema> */
	private $actions;
	
	/**
	 * @param array $contents
	 */
	protected function parse($contents) {
		$data = new Map($contents);
	
		$this->title = $data->get('title', '');
		$this->class = $data->get('class', '');
		
		$this->actions = new Map();
		if ($data->has('actions')) {
			foreach ($data->get('actions') as $name => $actionData) {
				$this->actions->set($name, new ActionSchema($name, $this->package, $actionData));
			}
		}
	}
	
	public function toArray() {
		$actions = [];
		foreach ($this->actions as $action) {
			$actions[$action->getName()] = $action->toArray();
		}
		
		return [
			'title' => $this->title,
			'class' => $this->class,
			'actions' => $actions
		];
	}
	
	public function getSlug() {
		if ($this->package->getVendor() == 'keeko') {
			return $this->package->getName();
		}
		
		return str_replace('/', '.', $this->package->getFullName());
	}
	
	public function hasAction($name) {
		return $this->actions->has($name);
	}
	
	/**
	 * @param ActionSchema $action
	 * @return $this
	 */
	public function addAction(ActionSchema $action) {
		$this->actions->set($action->getName(), $action);
		$action->setPackage($this->package);
		return $this;
	}
	
	/**
	 *
	 * @param string $name
	 * @return ActionSchema
	 */
	public function getAction($name) {
		return $this->actions->get($name);
	}
	
	/**
	 * Returns all action names
	 *
	 * @return Set
	 */
	public function getActionNames() {
		return $this->actions->keys();
	}
	
}

