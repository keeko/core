<?php
namespace keeko\core\response;

use keeko\framework\foundation\AbstractResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use keeko\core\model\ApplicationUri;
use keeko\core\model\Application;
use keeko\core\model\Localization;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\Parameters;

/**
 * Automatically generated JsonResponse for Updates an application-uri
 * 
 * @author gossi
 */
class ApplicationUriUpdateJsonResponse extends AbstractResponse {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param mixed $data
	 * @return JsonResponse
	 */
	public function run(Request $request, $data = null) {
		$params = new Parameters($request->query->all());
		$serializer = ApplicationUri::getSerializer();
		$resource = new Resource($data, $serializer);
		$resource = $resource->with($params->getInclude(['application', 'localization']));
		$resource = $resource->fields($params->getFields([
			'application-uri' => ApplicationUri::getSerializer()->getFields(),
			'application' => Application::getSerializer()->getFields(),
			'localization' => Localization::getSerializer()->getFields()
		]));
		$document = new Document($resource);

		return new JsonResponse($document->toArray(), 200);
	}
}
