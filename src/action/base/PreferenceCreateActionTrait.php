<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use phootwork\json\Json;
use keeko\core\model\Preference;
use keeko\framework\exceptions\ValidationException;

/**
 * Base methods for keeko\core\action\PreferenceCreateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait PreferenceCreateActionTrait {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$data = Json::decode($request->getContent());

		// hydrate
		$serializer = Preference::getSerializer();
		$preference = $serializer->hydrate(new Preference(), $data);

		// validate
		if (!$preference->validate()) {
			throw new ValidationException($preference->getValidationFailures());
		} else {
			$preference->save();
			return $this->response->run($request, $preference);
		}
	}
}
