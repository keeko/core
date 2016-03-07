<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Api;
use keeko\core\model\ApiQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ApiUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ApiUpdateActionTrait {

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
		$api = ApiQuery::create()->findOneById($id);

		// check existence
		if ($api === null) {
			throw new ResourceNotFoundException('Api not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Api::getSerializer();
		$api = $serializer->hydrate($api, $data);

		// validate
		if (!$api->validate()) {
			throw new ValidationException($api->getValidationFailures());
		} else {
			$api->save();
			return $this->response->run($request, $api);
		}
	}
}
