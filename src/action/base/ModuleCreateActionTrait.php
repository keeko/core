<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Module;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\ModuleCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ModuleCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Module::getSerializer();
		$module = $serializer->hydrate(new Module(), $data);

		// validate
		if (!$module->validate()) {
			throw new ValidationException($module->getValidationFailures());
		} else {
			$module->save();
			return $this->response->run($request, $module);
		}
	}
}
