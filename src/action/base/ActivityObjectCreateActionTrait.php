<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\ActivityObject;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\ActivityObjectCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ActivityObjectCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = ActivityObject::getSerializer();
		$activityObject = $serializer->hydrate(new ActivityObject(), $data);

		// validate
		if (!$activityObject->validate()) {
			throw new ValidationException($activityObject->getValidationFailures());
		} else {
			$activityObject->save();
			return $this->response->run($request, $activityObject);
		}
	}
}
