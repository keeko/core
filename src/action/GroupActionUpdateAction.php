<?php
namespace keeko\core\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Tobscure\JsonApi\Exception\InvalidParameterException;
use keeko\core\model\GroupActionQuery;
use keeko\core\model\GroupQuery;
use keeko\core\model\ActionQuery;

/**
 */
class GroupActionUpdateAction extends AbstractAction {

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

		// remove all relationships before
		GroupActionQuery::create()->filterByGroup($group)->delete();

		// add them
		foreach ($data as $entry) {
			if (!isset($entry['id'])) {
				throw new InvalidParameterException();
			}
			$action = ActionQuery::create()->findOneById($entry['id']);
			$group->addAction($action);
			$group->save();	
		}

		// run response
		return $this->response->run($request, $group);
	}
}
