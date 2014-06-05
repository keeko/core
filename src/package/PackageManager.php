<?php
namespace keeko\core\package;

use Composer\Package\Loader\JsonLoader;
use Composer\Package\Loader\ArrayLoader;
use keeko\core\exceptions\PackageException;
use Composer\Package\CompletePackageInterface;
use Composer\Package\Loader\ValidatingArrayLoader;
use Composer\Json\JsonFile;

class PackageManager {

	/**
	 * @var JsonLoader
	 */
	private $loader;

	private $cache = [];

	public function __construct() {
		$this->loader = new ArrayLoader();
	}

	private function load($file) {
		$config = JsonFile::parseJson(file_get_contents($file), $file);
		
		// fix version
		if (!isset($config['version'])) {
			$config['version'] = 'dev-master';
		}
		
		return $this->loader->load($config);
	}

	/**
	 *
	 * @return CompletePackageInterface
	 */
	private function getPackage($directory, $packageName) {
		if (isset($this->cache[$packageName])) {
			return $this->cache[$packageName];
		}
		
		$path = $directory . '/' . $packageName . '/composer.json';
		
		if (file_exists($path)) {
			$this->cache[$packageName] = $this->load($path);
			return $this->cache[$packageName];
		} else {
			throw new PackageException(sprintf('Package (%s) not found', $packageName));
		}
	}

	/**
	 *
	 * @return CompletePackageInterface
	 */
	public function getModulePackage($packageName) {
		return $this->getPackage(KEEKO_PATH_MODULES, $packageName);
	}

	/**
	 *
	 * @return CompletePackageInterface
	 */
	public function getApplicationPackage($packageName) {
		return $this->getPackage(KEEKO_PATH_APPS, $packageName);
	}
}