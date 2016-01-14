<?php
namespace keeko\core\installer;

use Composer\IO\IOInterface;
use keeko\core\model\Package;
use keeko\core\model\PackageQuery;
use keeko\core\service\ServiceContainer;
use Symfony\Component\EventDispatcher\EventDispatcher;
use keeko\core\schema\KeekoPackageSchema;

abstract class AbstractPackageInstaller {
	
	/** @var ServiceContainer */
	protected $service;
	
	/** @var EventDispatcher */
	protected $dispatcher;
	
	public function __construct(ServiceContainer $service = null) {
		$this->service = $service ?: new ServiceContainer();
		$this->dispatcher = $this->service->getDispatcher();
	}

	/**
	 * Installs a package
	 *
	 * @param IOInterface $io the IOInterface
	 * @param string $packageName
	 * @throws MissingOptionsException when required parameters for the package are missing
	 */
	abstract public function install(IOInterface $io, $packageName);

	abstract public function update(IOInterface $io, $packageName, $from, $to);

	abstract public function uninstall(IOInterface $io, $packageName);

	protected function updatePackage(Package &$model, KeekoPackageSchema $pkg) {
		$packageName = $pkg->getPackage()->getFullName();
		$result = PackageQuery::create()->filterByName($packageName)->count();
		$info = $this->service->getPackageManager()->getComposerPackage($packageName);
		
		if ($result == 0) {
			$model->setTitle($pkg->getTitle());
			$model->setDescription($info->getDescription());
			$model->setName($packageName);
			$model->setInstalledVersion($info->getPrettyVersion());
			$model->save();
		}
	}
	
	protected function getPackageSchema($packageName) {
		return $this->service->getPackageManager()->getPackage($packageName);
	}
}