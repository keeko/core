<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use phootwork\json\Json;
use keeko\core\model\User;
use keeko\core\model\UserQuery;
use keeko\framework\exceptions\ValidationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Base methods for keeko\core\action\UserUpdateAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait UserUpdateActionTrait {

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
		$user = UserQuery::create()->findOneById($id);

		// check existence
		if ($user === null) {
			throw new ResourceNotFoundException('User not found.');
		}

		// hydrate
		$data = Json::decode($request->getContent());
		$serializer = User::getSerializer();
		$user = $serializer->hydrate($user, $data);

		// validate
		if (!$user->validate()) {
			throw new ValidationException($user->getValidationFailures());
		} else {
			$user->save();
			return $this->response->run($request, $user);
		}
	}
}
