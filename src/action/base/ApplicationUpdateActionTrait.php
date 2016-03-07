<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Application;
use keeko\core\model\ApplicationQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ApplicationUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ApplicationUpdateActionTrait {

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
		$application = ApplicationQuery::create()->findOneById($id);

		// check existence
		if ($application === null) {
			throw new ResourceNotFoundException('Application not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Application::getSerializer();
		$application = $serializer->hydrate($application, $data);

		// validate
		if (!$application->validate()) {
			throw new ValidationException($application->getValidationFailures());
		} else {
			$application->save();
			return $this->response->run($request, $application);
		}
	}
}
