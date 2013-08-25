<?php
namespace keeko\core\installer;

use Composer\Script\Event;
use Composer\Script\PackageEvent;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;
use Composer\Package\CompletePackageInterface;
use Composer\Package\Package;
use Composer\Script\CommandEvent;
use Composer\Package\PackageInterface;
use Composer\Repository\WritableRepositoryInterface;

class ComposerInstaller {

	private static $isKeekoCorePresent = false;
	
	public static function preInstall(CommandEvent $event) {
		$packages = $event->getComposer()->getRepositoryManager()->getLocalRepository()->findPackages('keeko/core');
		self::$isKeekoCorePresent = count($packages) > 0;
	}
	
	public static function installPackage(PackageEvent $event) {
		if (!self::bootstrap()) {
			return;
		}
		
		$operation = $event->getOperation();
		if ($operation instanceof InstallOperation) {
			/* @var $operation InstallOperation */
			$package = $operation->getPackage();
			
			switch ($package->getType()) {
			case 'keeko-app':
				$installer = new AppInstaller();
				$installer->install($event->getIO(), $package);
				break;
				
			case 'keeko-module':
				$installer = new ModuleInstaller();
				$installer->install($event->getIO(), $package);
				break;
			}
		}
	}
	
	public static function updatePackage(PackageEvent $event) {
		if (!self::bootstrap()) {
			return;
		}
		
		$operation = $event->getOperation();
		if ($operation instanceof UpdateOperation) {
			/* @var $operation UpdateOperation */
			$initial = $operation->getInitialPackage();
			$target = $operation->getTargetPackage();

			switch ($target->getType()) {
			case 'keeko-app':
				$installer = new AppInstaller();
				$installer->update($event->getIO(), $initial, $target);
				break;

			case 'keeko-module':
				$installer = new ModuleInstaller();
				$installer->update($event->getIO(), $initial, $target);
				break;
			}
		}
	}
	
	public static function uninstallPackage(PackageEvent $event) {
		if (!self::bootstrap()) {
			return;
		}
		
		$operation = $event->getOperation();
		if ($operation instanceof UninstallOperation) {
			/* @var $operation UninstallOperation */
			$package = $operation->getPackage();

			switch ($package->getType()) {
			case 'keeko-app':
				$installer = new AppInstaller();
				$installer->uninstall($event->getIO(), $package);
				break;

			case 'keeko-module':
				$installer = new ModuleInstaller();
				$installer->uninstall($event->getIO(), $package);
				break;
			}
		}
	}
	
	private static function bootstrap() {
		$autoload = rtrim(getcwd(), '/\\') . '/vendor/autoload.php';
		
		if (file_exists($autoload)) {
			require_once rtrim(getcwd(), '/\\') . '/src/bootstrap.php';
			return true;
		}
		
		return false;
	}
}