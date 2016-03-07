<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\framework\exceptions\ValidationException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use phootwork\json\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\core\model\ExtensionQuery;

/**
 */
class ExtensionPackageUpdateAction extends AbstractAction {

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
		$extension = ExtensionQuery::create()->findOneById($id);
		$extension->setPackageId($data['id']);

		// validate
		if (!$extension->validate()) {
			throw new ValidationException($extension->getValidationFailures());
		} else {
			$extension->save();
			return $this->response->run($request, $extension);
		}
	}
}
