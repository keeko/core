<?php
namespace keeko\core\routing;

use keeko\core\exceptions\AppException;
use Symfony\Component\HttpFoundation\Request;
use keeko\core\model\ApplicationUriQuery;
use keeko\core\model\ApplicationUri;

class ApplicationRouter implements RouteMatcherInterface {

	private $destination;

	private $prefix;

	private $uri;

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
	 * @return ApplicationUri
	 */
	public function getUri() {
		return $this->uri;
	}

	/**
	 *
	 * @param Request $request        	
	 * @throws AppException
	 * @return ApplicationUri
	 */
	public function match($request) {
		$uri = null;
		// better loop. Maybe some priority on longer strings?
		// Or strings with more slashes?
		// better query on that?
		$uris = ApplicationUriQuery::create()->joinApplication()->filterByHttphost($request->getHttpHost())->find();
		
		foreach ($uris as $uri) {
			if (strpos($request->getRequestUri(), $uri->getBasepath()) !== false) {
				$this->destination = substr($request->getRequestUri(), strlen($uri->getBasepath()));
				$this->prefix = str_replace($request->getBasePath(), '', $uri->getBasePath());
				$this->uri = $uri;
				break;
			}
		}
		
		if (is_null($uri)) {
			throw new AppException(sprintf('No app found on %s', $request->getUri()), 404);
		}
		
		return $uri;
	}
}
