<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use keeko\core\model\GroupActionQuery;
use keeko\core\model\ActionQuery;
use keeko\core\model\GroupQuery;

/**
 */
class ActionGroupUpdateAction extends AbstractAction {

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
		$action = ActionQuery::create()->findOneById($id);

		if ($action === null) {
			throw new ResourceNotFoundException('action with id ' . $id . ' does not exist');
		}

		// remove all relationships before
		GroupActionQuery::create()->filterByAction($action)->delete();

		// add them
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				throw new InvalidParameterException();
			}
			$group = GroupQuery::create()->findOneById($entry['id']);
			$action->addGroup($group);
			$action->save();	
		}

		// run response
		return $this->response->run($request, $action);
	}
}
