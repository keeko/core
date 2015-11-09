<?php
namespace keeko\core\tests\schema;

use keeko\core\schema\PackageSchema;

class KeekoSchemaTest extends \PHPUnit_Framework_TestCase {
	
	public function testAppPackage() {
		$package = PackageSchema::fromFile(__DIR__ . '/fixture/app.json');
		
		$this->assertEquals('keeko-app', $package->getType());
		
		$keeko = $package->getKeeko();
		
		$this->assertTrue($keeko->isApp());
		$this->assertFalse($keeko->isModule());
		
		$app = $keeko->getApp();
		
		$this->assertEquals('Dummy App', $app->getTitle());
		$this->assertEquals('keeko\\app\\DummyApp', $app->getClass());
	}
	
	public function testModulePackage() {
		$package = PackageSchema::fromFile(__DIR__ . '/fixture/module.json');
		
		$this->assertEquals('keeko-module', $package->getType());
		
		$keeko = $package->getKeeko();
		$this->assertFalse($keeko->isApp());
		$this->assertTrue($keeko->isModule());
		
		$module = $keeko->getModule();
		$this->assertEquals('Dummy Module', $module->getTitle());
		$this->assertEquals('keeko\\module\\DummyModule', $module->getClass());
		$this->assertEquals('module', $module->getSlug());
		
		$this->assertTrue($module->hasAction('dashboard'));
		$dashboard = $module->getAction('dashboard');
		$this->assertEquals('Admin overview', $dashboard->getTitle());
		$this->assertEquals('keeko\\module\\actions\\DashboardAction', $dashboard->getClass());
		$this->assertEquals(1, $dashboard->getAcl()->size());
		$this->assertTrue($dashboard->hasAcl('admin'));
		
		$this->assertTrue($dashboard->hasResponse('json'));
		$this->assertEquals('keeko\\module\\responses\\DashboardJsonResponse', $dashboard->getResponse('json'));
	}

}
