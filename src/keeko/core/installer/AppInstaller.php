<?php
namespace keeko\core\installer;

use Composer\Package\PackageInterface;

class AppInstaller implements KeekoPackageInstallerInterface {
	
	public function install(PackageInterface $package) {
		$extra = $package->getExtra();
		
		if (array_key_exists('keeko', $extra) && array_key_exists('app-class', $extra['keeko'])) {
			$appClass = $extra['keeko']['app-class'];
			$app = new $appClass();
			$app->install();
		}
	}
	
	public function update(PackageInterface $initial, PackageInterface $target) {
		$extra = $target->getExtra();
		
		if (array_key_exists('keeko', $extra) && array_key_exists('app-class', $extra['keeko'])) {
			$appClass = $extra['keeko']['app-class'];
			$app = new $appClass();
			$app->update($initial, $target);
		}
	}
	
	public function uninstall(PackageInterface $package) {
		$extra = $package->getExtra();
		
		if (array_key_exists('keeko', $extra) && array_key_exists('app-class', $extra['keeko'])) {
			$appClass = $extra['keeko']['app-class'];
			$app = new $appClass();
			$app->uninstall();
		}
	}
}