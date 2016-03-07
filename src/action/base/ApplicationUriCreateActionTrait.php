<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\ApplicationUri;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\ApplicationUriCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ApplicationUriCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = ApplicationUri::getSerializer();
		$applicationUri = $serializer->hydrate(new ApplicationUri(), $data);

		// validate
		if (!$applicationUri->validate()) {
			throw new ValidationException($applicationUri->getValidationFailures());
		} else {
			$applicationUri->save();
			return $this->response->run($request, $applicationUri);
		}
	}
}
