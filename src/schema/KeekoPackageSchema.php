<?php
namespace keeko\core\schema;

abstract class KeekoPackageSchema extends SubSchema {

	/** @var string */
	protected $title;
	
	/** @var string */
	protected $class;
	
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