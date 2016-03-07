<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Extension;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\ExtensionCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ExtensionCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Extension::getSerializer();
		$extension = $serializer->hydrate(new Extension(), $data);

		// validate
		if (!$extension->validate()) {
			throw new ValidationException($extension->getValidationFailures());
		} else {
			$extension->save();
			return $this->response->run($request, $extension);
		}
	}
}
