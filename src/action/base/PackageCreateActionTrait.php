<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Package;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\PackageCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait PackageCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Package::getSerializer();
		$package = $serializer->hydrate(new Package(), $data);

		// validate
		if (!$package->validate()) {
			throw new ValidationException($package->getValidationFailures());
		} else {
			$package->save();
			return $this->response->run($request, $package);
		}
	}
}
