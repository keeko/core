<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Localization;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\LocalizationCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait LocalizationCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Localization::getSerializer();
		$localization = $serializer->hydrate(new Localization(), $data);

		// validate
		if (!$localization->validate()) {
			throw new ValidationException($localization->getValidationFailures());
		} else {
			$localization->save();
			return $this->response->run($request, $localization);
		}
	}
}
