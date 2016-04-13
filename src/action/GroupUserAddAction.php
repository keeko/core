<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use keeko\core\model\GroupQuery;
use keeko\core\model\UserQuery;

/**
 */
class GroupUserAddAction extends AbstractAction {

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
		$group = GroupQuery::create()->findOneById($id);

		if ($group === null) {
			throw new ResourceNotFoundException('group with id ' . $id . ' does not exist');
		} 

		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				throw new InvalidParameterException();
			}
			$user = UserQuery::create()->findOneById($entry['id']);
			$group->addUser($user);
			$group->save();	
		}

		// run response
		return $this->response->run($request, $group);
	}
}
