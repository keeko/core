<?php
namespace keeko\core\tests\schema;

use keeko\core\schema\PackageSchema;
use phootwork\collection\Map;
use phootwork\json\Json;

class ComposerSchemaTest extends \PHPUnit_Framework_TestCase {
	
	public function testReadBasicPackage() {
		$package = PackageSchema::fromFile(__DIR__ . '/fixture/basic.json');
		
		$this->assertBasicPackage($package);
	}

	private function assertBasicPackage(PackageSchema $package) {
		$this->assertEquals('basic/package', $package->getFullName());
		$this->assertEquals('package', $package->getName());
		$this->assertEquals('basic', $package->getVendor());
		$this->assertEquals('I am just a dummy', $package->getDescription());
		$this->assertEquals('package', $package->getType());
		
		// authors
		$authors = $package->getAuthors();
		$gossi = $authors->get(0);
		$this->assertEquals(1, $authors->size());
		$this->assertEquals('gossi', $gossi->getName());
		
		// autoload
		$autoload = $package->getAutoload();
		$psr4 = $autoload->getPsr4();
		$this->assertTrue($autoload->getClassmap()->isEmpty());
		$this->assertTrue($autoload->getFiles()->isEmpty());
		$this->assertTrue($autoload->getPsr0()->isEmpty());
		$this->assertEquals('src/', $psr4->getPath('basic\\package\\'));
		$this->assertTrue($psr4->hasNamespace('basic\\package\\'));
		
		// require
		$require = $package->getRequire();
		$this->assertFalse($require->isEmpty());
		$this->assertTrue($require->has('phootwork/collection'));
		
		// require-dev
		$requireDev = $package->getRequireDev();
		$this->assertFalse($requireDev->isEmpty());
		$this->assertTrue($requireDev->has('phpunit/phpunit'));
		
		// extra
		$extra = $package->getExtra();
		$this->assertTrue($extra->has('moop'));
		$this->assertEquals('value', $extra->get('moop'));
		
		$this->assertTrue($extra->get('doop') instanceof Map);
		$this->assertEquals('other-value', $extra->get('doop')->get('some'));
	}
	
	public function testWriteBasicPackage() {
		$package = PackageSchema::fromFile(__DIR__ . '/fixture/basic.json');
		$json = Json::encode($package->toArray(), Json::PRETTY_PRINT | Json::UNESCAPED_SLASHES);
		$expected = file_get_contents(__DIR__ . '/fixture/basic.json');
		
		$this->assertEquals($expected, $json);
	}
	
	public function testExtendedPackage() {
		$package = PackageSchema::fromFile(__DIR__ . '/fixture/extended.json');
		$json = Json::encode($package->toArray(), Json::PRETTY_PRINT | Json::UNESCAPED_SLASHES);
		$expected = file_get_contents(__DIR__ . '/fixture/extended.json');
		
		$this->assertEquals($expected, $json);
	}
	
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
		$this->assertEquals('keeko.module', $module->getSlug());
		
		$this->assertTrue($module->hasAction('dashboard'));
		$dashboard = $module->getAction('dashboard');
		$this->assertEquals('Admin overview', $dashboard->getTitle());
		$this->assertEquals('keeko\\module\\actions\\DashboardAction', $dashboard->getClass());
		$this->assertEquals(1, $dashboard->getAcls()->size());
		$this->assertTrue($dashboard->hasAcl('admin'));
		
		$this->assertTrue($dashboard->hasResponse('json'));
		$this->assertEquals('keeko\\module\\responses\\DashboardJsonResponse', $dashboard->getResponse('json'));
	}

}
