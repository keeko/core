<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Group;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\GroupCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait GroupCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Group::getSerializer();
		$group = $serializer->hydrate(new Group(), $data);

		// validate
		if (!$group->validate()) {
			throw new ValidationException($group->getValidationFailures());
		} else {
			$group->save();
			return $this->response->run($request, $group);
		}
	}
}
