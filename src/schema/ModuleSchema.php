<?php
namespace keeko\core\schema;

use phootwork\lang\Arrayable;
use phootwork\collection\CollectionUtils;
use phootwork\collection\Map;
use phootwork\collection\Set;

class ModuleSchema implements Arrayable {
	
	/** @var string */
	private $title;
	
	/** @var string */
	private $class;
	
	/** @var string */
	private $slug;
	
	/** @var Map<string, ActionSchema> */
	private $actions;
	
	public function __construct($contents = []) {
		$this->parse($contents);
	}
	
	/**
	 * @param array $contents
	 */
	private function parse($contents) {
		/* @var $data Map */
		$data = CollectionUtils::fromCollection($contents);
	
		$this->title = $data->get('title', '');
		$this->class = $data->get('class', '');
		$this->slug = $data->get('slug', '');
		
		$this->actions = new Map();
		if ($data->has('actions')) {
			foreach ($data->get('actions') as $name => $actionData) {
				$this->actions->set($name, new ActionSchema($name, $actionData));
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
			'slug' => $this->slug,
			'actions' => $actions
		];
	}

	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function setClass($class) {
		$this->class = $class;
		return $this;
	}
	
	public function getSlug() {
		return $this->slug;
	}
	
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
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

