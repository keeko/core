<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use keeko\core\model\UserQuery;
use keeko\core\model\GroupQuery;

/**
 */
class UserGroupAddAction extends AbstractAction {

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
		$body = $request->getContent();
		if (!isset($body['data'])) {
			throw new InvalidParameterException();
		}
		$data = $body['data'];

		$id = $this->getParam('id');
		$user = UserQuery::create()->findOneById($id);

		if ($user === null) {
			throw new ResourceNotFoundException('user with id ' . $id . ' does not exist');
		} 

		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				throw new InvalidParameterException();
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$user->addGroup($group);
			$user->save();	
		}

		// run response
		return $this->response->run($request, $user);
	}
}
