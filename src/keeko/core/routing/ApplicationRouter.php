<?php
namespace keeko\core\routing;

use keeko\core\exceptions\AppException;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\entities\GatewayUriQuery;
use keeko\core\entities\GatewayUri;

class ApplicationRouter implements RouteMatcherInterface {

	private $destination;
	private $prefix;

	public function __construct() {
	}

	public function getDestination() {
		return $this->destination;
	}

	public function getPrefix() {
		return $this->prefix;
	}

	/**
	 *
	 *
	 * @param Request $request
	 * @throws AppException
	 * @return GatewayUri
	 */
	public function match(Request $request) {
		$uri = null;
		// better loop. Maybe some priority on longer strings?
		// Or strings with more slashes?
		// better query on that?
		$uris = GatewayUriQuery::create()
			->joinApp()
			->filterByHttphost($request->getHttpHost())
			->find();

		foreach ($uris as $uri) {
			if ($pos = strpos($request->getRequestUri(), $uri->getBasepath()) !== false) {
				$this->destination = substr($request->getRequestUri(), strlen($uri->getBasepath()));
				$this->prefix = str_replace($request->getBasePath(), '', $uri->getBasePath());
				break;
			}
		}

		if (is_null($uri)) {
			throw new AppException(sprintf('No app found on %s', $request->getUri()), 404);
		}

		return $uri;
	}
}
