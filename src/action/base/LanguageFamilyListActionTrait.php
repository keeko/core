<?php
namespace keeko\core\action\base;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\core\model\LanguageFamily;
use keeko\core\model\LanguageFamilyQuery;
use keeko\framework\utils\NameUtils;
use Tobscure\JsonApi\Parameters;

/**
 * Base methods for keeko\core\action\LanguageFamilyListAction
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
trait LanguageFamilyListActionTrait {

	/**
	 * Applies filtering on the query.
	 * 
	 * Overwrite this method on the action class to implement this functionality
	 * 
	 * @param LanguageFamilyQuery $query
	 */
	public function applyFilter(LanguageFamilyQuery $query) {
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
		$query = LanguageFamilyQuery::create();

		// sorting
		$sort = $params->getSort(LanguageFamily::getSerializer()->getSortFields());
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
		$languageFamily = $query->paginate($page, $pageSize);

		// run response
		return $this->response->run($request, $languageFamily);
	}
}
