<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Activity;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\ActivityCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ActivityCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Activity::getSerializer();
		$activity = $serializer->hydrate(new Activity(), $data);

		// validate
		if (!$activity->validate()) {
			throw new ValidationException($activity->getValidationFailures());
		} else {
			$activity->save();
			return $this->response->run($request, $activity);
		}
	}
}
