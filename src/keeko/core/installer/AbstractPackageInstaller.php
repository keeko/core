<?php
namespace keeko\core\installer;

use keeko\core\installer\KeekoPackageInstallerInterface;
use Composer\Package\CompletePackageInterface;
use keeko\core\entities\Package;

abstract class AbstractPackageInstaller implements KeekoPackageInstallerInterface {

	protected function updatePackage(Package $target, CompletePackageInterface $package) {
		$target->setDescription($package->getDescription());
		$target->setName($package->getName());
		$target->setInstalledVersion($package->getPrettyVersion());
		$target->save();
	}

}