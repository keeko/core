<?php
namespace keeko\core\installer;

use Composer\Package\CompletePackageInterface;
use Composer\IO\IOInterface;

interface KeekoPackageInstallerInterface {
	
	public function install(IOInterface $io, CompletePackageInterface $package);
	
	public function update(IOInterface $io, CompletePackageInterface $initial, CompletePackageInterface $target);
	
	public function uninstall(IOInterface $io, CompletePackageInterface $package);
}