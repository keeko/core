<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Action;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\ActionCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ActionCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Action::getSerializer();
		$action = $serializer->hydrate(new Action(), $data);

		// validate
		if (!$action->validate()) {
			throw new ValidationException($action->getValidationFailures());
		} else {
			$action->save();
			return $this->response->run($request, $action);
		}
	}
}
