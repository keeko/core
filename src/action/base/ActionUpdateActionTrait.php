<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Action;
use keeko\core\model\ActionQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\ActionUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait ActionUpdateActionTrait {

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
		$action = ActionQuery::create()->findOneById($id);

		// check existence
		if ($action === null) {
			throw new ResourceNotFoundException('Action not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Action::getSerializer();
		$action = $serializer->hydrate($action, $data);

		// validate
		if (!$action->validate()) {
			throw new ValidationException($action->getValidationFailures());
		} else {
			$action->save();
			return $this->response->run($request, $action);
		}
	}
}
