<?php
namespace keeko\core\routing;

interface RouteMatcherInterface {

	/**
	 * Matches a route for the given request
	 * 
	 * @param mixed $request
	 * @return mixed data for the route
	 */
	public function match($request);
}