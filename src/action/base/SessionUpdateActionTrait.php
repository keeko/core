<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Session;
use keeko\core\model\SessionQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\SessionUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait SessionUpdateActionTrait {

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
		$session = SessionQuery::create()->findOneById($id);

		// check existence
		if ($session === null) {
			throw new ResourceNotFoundException('Session not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Session::getSerializer();
		$session = $serializer->hydrate($session, $data);

		// validate
		if (!$session->validate()) {
			throw new ValidationException($session->getValidationFailures());
		} else {
			$session->save();
			return $this->response->run($request, $session);
		}
	}
}
