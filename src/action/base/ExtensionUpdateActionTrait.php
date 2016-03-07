<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Extension;
use keeko\core\model\ExtensionQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ExtensionUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ExtensionUpdateActionTrait {

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
		$extension = ExtensionQuery::create()->findOneById($id);

		// check existence
		if ($extension === null) {
			throw new ResourceNotFoundException('Extension not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Extension::getSerializer();
		$extension = $serializer->hydrate($extension, $data);

		// validate
		if (!$extension->validate()) {
			throw new ValidationException($extension->getValidationFailures());
		} else {
			$extension->save();
			return $this->response->run($request, $extension);
		}
	}
}
