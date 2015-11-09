<?php
namespace keeko\core\schema;

use phootwork\lang\Arrayable;

abstract class SubSchema implements Arrayable {

	/** @var PackageSchema */
	protected $package;
	
	public function __construct(PackageSchema $root, $contents = []) {
		$this->package = $root;
		$this->parse($contents);
	}
	
	protected function parse($contents) {
	}
	
	/**
	 * @return PackageSchema
	 */
	protected function getPackage() {
		return $this->package;
	}
	
	public function setPackage(RootSchema $package) {
		$this->package = $package;
	}
}