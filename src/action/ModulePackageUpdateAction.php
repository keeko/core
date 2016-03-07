<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\framework\exceptions\ValidationException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use phootwork\json\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\core\model\ModuleQuery;

/**
 */
class ModulePackageUpdateAction extends AbstractAction {

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
		$module = ModuleQuery::create()->findOneById($id);
		$module->setPackageId($data['id']);

		// validate
		if (!$module->validate()) {
			throw new ValidationException($module->getValidationFailures());
		} else {
			$module->save();
			return $this->response->run($request, $module);
		}
	}
}
