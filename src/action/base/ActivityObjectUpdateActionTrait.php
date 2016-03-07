<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\ActivityObject;
use keeko\core\model\ActivityObjectQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ActivityObjectUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ActivityObjectUpdateActionTrait {

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
		$activityObject = ActivityObjectQuery::create()->findOneById($id);

		// check existence
		if ($activityObject === null) {
			throw new ResourceNotFoundException('ActivityObject not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = ActivityObject::getSerializer();
		$activityObject = $serializer->hydrate($activityObject, $data);

		// validate
		if (!$activityObject->validate()) {
			throw new ValidationException($activityObject->getValidationFailures());
		} else {
			$activityObject->save();
			return $this->response->run($request, $activityObject);
		}
	}
}
