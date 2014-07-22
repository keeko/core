<?php
namespace keeko\core\installer;

use Composer\Package\CompletePackageInterface;
use keeko\core\model\Package;
use Composer\IO\IOInterface;
use keeko\core\model\PackageQuery;

abstract class AbstractPackageInstaller {

	/**
	 * Installs a package
	 *
	 * @param
	 *        	IOInterface the IOInterface
	 * @param
	 *        	CompletePackageInterface package information
	 * @throws MissingOptionsException when required parameters for the package are missing
	 */
	abstract public function install(IOInterface $io, CompletePackageInterface $package);

	abstract public function update(IOInterface $io, CompletePackageInterface $initial, CompletePackageInterface $target);

	abstract public function uninstall(IOInterface $io, CompletePackageInterface $package);

	protected function updatePackage(Package &$package, CompletePackageInterface $packageInfo) {
		$result = PackageQuery::create()->filterByName($package->getName())->count();
		
		if ($result == 0) {
			$package->setDescription($packageInfo->getDescription());
			$package->setName($packageInfo->getName());
			$package->setInstalledVersion($packageInfo->getPrettyVersion());
			$package->save();
		}
	}
}