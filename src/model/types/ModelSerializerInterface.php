<?php
namespace keeko\core\model\types;

use Tobscure\JsonApi\SerializerInterface;

interface ModelSerializerInterface extends SerializerInterface {
	
	/**
	 * Returns an array representation of the model
	 *
	 * @return array
	 */
	public function toArray();
	
	public function hydrate($data, $model);
	
	/**
	 * Returns an array of short names to API type name.
	 *
	 * Example:
	 * return ['users' => 'user/users'];
	 */
	public function getRelationships();
}