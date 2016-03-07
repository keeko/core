<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Activity;
use keeko\core\model\ActivityQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ActivityUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ActivityUpdateActionTrait {

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
		$activity = ActivityQuery::create()->findOneById($id);

		// check existence
		if ($activity === null) {
			throw new ResourceNotFoundException('Activity not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Activity::getSerializer();
		$activity = $serializer->hydrate($activity, $data);

		// validate
		if (!$activity->validate()) {
			throw new ValidationException($activity->getValidationFailures());
		} else {
			$activity->save();
			return $this->response->run($request, $activity);
		}
	}
}
