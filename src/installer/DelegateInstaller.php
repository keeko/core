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

class DelegateInstaller {

	public static function installPackage(PackageEvent $event) {
		if (!self::bootstrap()) {
			return;
		}
		
		$operation = $event->getOperation();
		if ($operation instanceof InstallOperation) {
			/* @var $operation InstallOperation */
			$package = $operation->getPackage();
			$installer = self::getInstaller($package);
			$installer->install($event->getIO(), $package);
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
			$installer = self::getInstaller($target);
			$installer->update($event->getIO(), $initial, $target);
		}
	}

	public static function uninstallPackage(PackageEvent $event) {
		if (!self::bootstrap()) {
			return;
		}
		
		$operation = $event->getOperation();
		if ($operation instanceof UninstallOperation) {
			$package = $operation->getPackage();
			$installer = self::getInstaller($package);
			$installer->uninstall($event->getIO(), $package);
		}
	}

	/**
	 * Returns the installer for a given type
	 *
	 * @param
	 *        	PackageInterface
	 * @return AbstractPackageInstaller
	 */
	private static function getInstaller(PackageInterface $package) {
		switch ($package->getType()) {
			case 'keeko-app':
				return new AppInstaller();
				break;
			case 'keeko-module':
				return new ModuleInstaller();
				break;
			default:
				return new DummyInstaller();
				break;
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