<?php
namespace keeko\core\model\serializer;

use keeko\core\model\types\ModelSerializerInterface;
use Tobscure\JsonApi\Relationship;

abstract class AbstractSerializer implements ModelSerializerInterface {
	
	public function getAttributes($model, $fields = null) {
		return [];
	}
	
	public function getSelf($model) {
		return '%apiurl%' . $this->getType($model) . '/' . $this->getId($model);
	}
	
	public function getLinks($model) {
		return [
			'self' => $this->getSelf($model)
		];
	}
	
	public function getRelationship($model, $name) {
		// strip namespace
		if (strstr($name, '/') !== false) {
			$name = substr($name, strpos($name, '/') + 1);
		}
		
		$method = $name;
		
		// to camel case
		if (strstr($method, '-') !== false) {
			$method = lcfirst(implode('', array_map('ucfirst', explode('-', $method))));
		}
		
		if (method_exists($this, $method)) {
			$relationship = $this->$method($model, $name);
			if ($relationship !== null && !($relationship instanceof Relationship)) {
				throw new \LogicException('Relationship method must return null or an instance of '
						. Relationship::class);
			}
			return $relationship;
		}
	}
	
	protected function addRelationshipSelfLink(Relationship $relationship, $model, $related) {
		$links = $relationship->getLinks();
		$links = $links + [
			'self' => $this->getSelf($model) . '/relationships/' . $related
		];
		$relationship->setLinks($links);
		return $relationship;
	}
	
	public function toArray($model) {
		$id = ['id' => $this->getId($model)];
		$attributes = $this->getAttributes($model);
		return $id + $attributes;
	}
	
	public function hydrateRelationships($model, $data) {
		$relationships = isset($data['relationships']) ? $data['relationships'] : [];
		
		foreach (array_keys($this->getRelationships()) as $rel) {
			if (isset($relationships[$rel]) && isset($relationships[$rel]['data'])) {
				$method = 'set' . ucFirst($rel);
				if (method_exists($this, $method)) {
					$this->$method($model, $relationships[$rel]['data']);
				}
			}
		}
	}
}