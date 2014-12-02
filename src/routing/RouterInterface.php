<?php
namespace keeko\core\routing;

use keeko\core\routing\RouteMatcherInterface;
use keeko\core\routing\RouteGeneratorInterface;

interface RouterInterface extends RouteGeneratorInterface, RouteMatcherInterface {
}