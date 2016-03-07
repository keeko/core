<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Module;
use keeko\core\model\ModuleQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ModuleUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ModuleUpdateActionTrait {

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
		$module = ModuleQuery::create()->findOneById($id);

		// check existence
		if ($module === null) {
			throw new ResourceNotFoundException('Module not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Module::getSerializer();
		$module = $serializer->hydrate($module, $data);

		// validate
		if (!$module->validate()) {
			throw new ValidationException($module->getValidationFailures());
		} else {
			$module->save();
			return $this->response->run($request, $module);
		}
	}
}
