<?php
namespace keeko\core\schema;

use phootwork\lang\Arrayable;
use phootwork\collection\CollectionUtils;

class AppSchema implements Arrayable {

	/** @var string */
	private $title;
	
	/** @var string */
	private $class;
	
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
	}
	
	public function toArray() {
		return [
			'title' => $this->title,
			'class' => $this->class
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
}