<?php
namespace keeko\core\schema;

use phootwork\collection\CollectionUtils;

class CodegenSchema extends AbstractSchema {
	
	/** @var Map */
	private $data;
	
	public function __construct($contents = []) {
		$this->parse($contents);
	}
	
	private function parse($contents) {
		$this->data = CollectionUtils::toMap($contents);
	}
	
	private function getArray($modelName, $io, $section) {
		if ($this->data->has($modelName)
				&& $this->data->get($modelName)->has($io)
				&& $this->data->get($modelName)->get($io)->has($section)) {
			return $this->data->get($modelName)->get($io)->has($section)->toArray();
		}

		return [];
	}
	
	public function getWriteConversion($modelName) {
		return $this->getArray($modelName, 'write', 'conversion');
	}
	
	public function getWriteFilter($modelName) {
		return $this->getArray($modelName, 'write', 'filter');
	}
	
	public function getReadFilter($modelName) {
		return $this->getArray($modelName, 'read', 'filter');
	}
}
