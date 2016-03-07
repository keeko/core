<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\model\Subdivision;
use keeko\core\model\SubdivisionQuery;
use keeko\framework\utils\NameUtils;
use Tobscure\JsonApi\Parameters;

/**
 * Base methods for keeko\core\action\SubdivisionListAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait SubdivisionListActionTrait {

	/**
	 * Applies filtering on the query.
	 * 
	 * Overwrite this method on the action class to implement this functionality
	 * 
	 * @param SubdivisionQuery $query
	 */
	public function applyFilter(SubdivisionQuery $query) {
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureParams(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'page_size' => 50,
		]);
	}

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		// create query
		$params = new Parameters($request->query->all());
		$page = $params->getPage('number');
		$pageSize = $this->getParam('page_size');
		$query = SubdivisionQuery::create();

		// sorting
		$sort = $params->getSort(Subdivision::getSerializer()->getSortFields());
		foreach ($sort as $field => $order) {
			$method = 'orderBy' . NameUtils::toStudlyCase($field);
			$query->$method($order);
		}

		// filtering
		$filter = $this->getParam('filter');
		if (!empty($filter)) {
			$this->applyFilter($query);
		}

		// paginate
		$subdivision = $query->paginate($page, $pageSize);

		// run response
		return $this->response->run($request, $subdivision);
	}
}
