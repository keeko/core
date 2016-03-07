<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Api;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\ApiCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ApiCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Api::getSerializer();
		$api = $serializer->hydrate(new Api(), $data);

		// validate
		if (!$api->validate()) {
			throw new ValidationException($api->getValidationFailures());
		} else {
			$api->save();
			return $this->response->run($request, $api);
		}
	}
}
