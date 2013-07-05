<?php
namespace keeko\core\installer;

use Composer\Package\PackageInterface;

interface KeekoPackageInstallerInterface {
	
	public function install(PackageInterface $package);
	
	public function update(PackageInterface $initial, PackageInterface $target);
	
	public function uninstall(PackageInterface $package);
}