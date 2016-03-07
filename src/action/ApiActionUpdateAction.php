<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\framework\exceptions\ValidationException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use phootwork\json\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\core\model\ApiQuery;

/**
 */
class ApiActionUpdateAction extends AbstractAction {

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
		$body = Json::decode($request->getContent());
		if (!isset($body['data'])) {
			throw new InvalidParameterException();
		}
		$data = $body['data'];

		if (!isset($data['id'])) {
			throw new InvalidParameterException();
		}

		$id = $this->getParam('id');
		$api = ApiQuery::create()->findOneById($id);
		$api->setActionId($data['id']);

		// validate
		if (!$api->validate()) {
			throw new ValidationException($api->getValidationFailures());
		} else {
			$api->save();
			return $this->response->run($request, $api);
		}
	}
}
