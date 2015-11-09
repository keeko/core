<?php
namespace keeko\core\schema;

use phootwork\collection\Map;

class AppSchema extends KeekoPackageSchema {
	
	/**
	 * @param array $contents
	 */
	protected function parse($contents) {
		$data = new Map($contents);
	
		$this->title = $data->get('title', '');
		$this->class = $data->get('class', '');
	}
	
	public function toArray() {
		return [
			'title' => $this->title,
			'class' => $this->class
		];
	}
	
}