<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\Group;
use keeko\core\model\GroupQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\GroupUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait GroupUpdateActionTrait {

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
		$group = GroupQuery::create()->findOneById($id);

		// check existence
		if ($group === null) {
			throw new ResourceNotFoundException('Group not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = Group::getSerializer();
		$group = $serializer->hydrate($group, $data);

		// validate
		if (!$group->validate()) {
			throw new ValidationException($group->getValidationFailures());
		} else {
			$group->save();
			return $this->response->run($request, $group);
		}
	}
}
