<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\ApplicationUri;
use keeko\core\model\ApplicationUriQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ApplicationUriUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ApplicationUriUpdateActionTrait {

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
		$applicationUri = ApplicationUriQuery::create()->findOneById($id);

		// check existence
		if ($applicationUri === null) {
			throw new ResourceNotFoundException('ApplicationUri not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = ApplicationUri::getSerializer();
		$applicationUri = $serializer->hydrate($applicationUri, $data);

		// validate
		if (!$applicationUri->validate()) {
			throw new ValidationException($applicationUri->getValidationFailures());
		} else {
			$applicationUri->save();
			return $this->response->run($request, $applicationUri);
		}
	}
}
