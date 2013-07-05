<?php
namespace keeko\core\installer;

// require '../../../../../src/bootstrap.php';

use Composer\Script\Event;
use Composer\Script\PackageEvent;
use Composer\DependencyResolver\Operation\InstallOperation;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\DependencyResolver\Operation\UninstallOperation;

class ComposerInstaller {

	public static function installPackage(PackageEvent $event) {
		self::bootstrap();
		$operation = $event->getOperation();
		$event->getIO()->write('Package installieren ('.$operation->getPackage()->getName().'), in: ' . getcwd(), true);
		
		if ($operation instanceof InstallOperation) {
			/* @var $operation InstallOperation */
			$package = $operation->getPackage();
			
			switch ($package->getType()) {
			case 'keeko-app':
				$installer = new AppInstaller();
				$installer->install($package);
				break;
				
			case 'keeko-module':
				$installer = new ModuleInstaller();
				$installer->install($package);
				break;
			}
		}
	}
	
	public static function updatePackage(PackageEvent $event) {
		self::bootstrap();
		$operation = $event->getOperation();
		
		$event->getIO()->write('Package updaten ('.$operation->getTargetPackage()->getName().'), in: ' . getcwd(), true);
	
		if ($operation instanceof UpdateOperation) {
			/* @var $operation UpdateOperation */
			$initial = $operation->getInitialPackage();
			$target = $operation->getTargetPackage();
				
			switch ($target->getType()) {
			case 'keeko-app':
				$installer = new AppInstaller();
				$installer->update($initial, $target);
				break;

			case 'keeko-module':
				$installer = new ModuleInstaller();
				$installer->update($initial, $target);
				break;
			}
		}
	}
	
	public static function uninstallPackage(PackageEvent $event) {
		self::bootstrap();
		$operation = $event->getOperation();
		$event->getIO()->write('Package deinstallieren, hihihi', true);
	
		if ($operation instanceof UninstallOperation) {
			/* @var $operation UninstallOperation */
			$package = $operation->getPackage();
				
			switch ($package->getType()) {
			case 'keeko-app':
				$installer = new AppInstaller();
				$installer->uninstall($package);
				break;

			case 'keeko-module':
				$installer = new ModuleInstaller();
				$installer->uninstall($package);
				break;
			}
		}
	}
	
	private static function bootstrap() {
		// check whether bootstrapping is needed
		if (!defined('KEEKO_PATH')) {
			require_once rtrim(getcwd(), '/\\') . '/src/bootstrap.php';
		}
	}
}