<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Preference;
use keeko\core\model\PreferenceQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\PreferenceUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait PreferenceUpdateActionTrait {

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureParams(OptionsResolver $resolver) {
		$resolver->setRequired(['id']);
	}

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		// read
		$id = $this->getParam('id');
		$preference = PreferenceQuery::create()->findOneById($id);

		// check existence
		if ($preference === null) {
			throw new ResourceNotFoundException('Preference not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Preference::getSerializer();
		$preference = $serializer->hydrate($preference, $data);

		// validate
		if (!$preference->validate()) {
			throw new ValidationException($preference->getValidationFailures());
		} else {
			$preference->save();
			return $this->response->run($request, $preference);
		}
	}
}
