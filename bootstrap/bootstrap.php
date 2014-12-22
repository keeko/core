<?php
use keeko\core\config\DatabaseConfiguration;
use Symfony\Component\Config\FileLocator;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use keeko\core\config\DevelopmentConfiguration;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use keeko\core\config\GeneralConfiguration;

define('KEEKO_PRODUCTION', 'production');
define('KEEKO_DEVELOPMENT', 'development');

define('KEEKO_PATH_CONFIG', KEEKO_PATH . DIRECTORY_SEPARATOR . 'config');
define('KEEKO_PATH_MODULES', KEEKO_PATH . DIRECTORY_SEPARATOR . 'modules');
define('KEEKO_PATH_APPS', KEEKO_PATH . DIRECTORY_SEPARATOR . 'apps');
define('KEEKO_PATH_DESIGNS', KEEKO_PATH . DIRECTORY_SEPARATOR . 'designs');


// load config
$locator = new FileLocator(KEEKO_PATH_CONFIG);
$devConfig = new DevelopmentConfiguration($locator);
$dbConfig = new DatabaseConfiguration($locator);
$generalConfig = new GeneralConfiguration($locator);
$loader = new DelegatingLoader(new LoaderResolver([$devConfig, $dbConfig, $generalConfig]));

try {
	$loader->load(KEEKO_PATH_CONFIG . '/development.yaml');
	$loader->load(KEEKO_PATH_CONFIG . '/database.yaml');
	$loader->load(KEEKO_PATH_CONFIG . '/general.yaml');
} catch (FileLoaderLoadException $e) {}


// development config
define('KEEKO_ENVIRONMENT', $devConfig->isLoaded() ? KEEKO_DEVELOPMENT : KEEKO_PRODUCTION);

if (KEEKO_ENVIRONMENT == KEEKO_DEVELOPMENT) {
	error_reporting(E_ALL | E_STRICT);
}


// database config
if (!$dbConfig->isLoaded()) {
	echo 'No database.yaml found. Please rename database.yaml.dist to database.yaml and set the appropriate values for your environment.';
	exit;
}
$serviceContainer = Propel::getServiceContainer();
$serviceContainer->setAdapterClass('keeko', 'mysql');
$manager = new ConnectionManagerSingle();
$manager->setConfiguration([
	'dsn'      => 'mysql:host=' . $dbConfig->getHost() . ';dbname=' . $dbConfig->getDatabase(),
	'user'     => $dbConfig->getUser(),
	'password' => $dbConfig->getPassword()
]);
$manager->setName('keeko');
$serviceContainer->setConnectionManager('keeko', $manager);
$serviceContainer->setDefaultDatasource('keeko');

if (KEEKO_ENVIRONMENT == KEEKO_DEVELOPMENT) {
	$con = Propel::getWriteConnection('keeko');
	$con->useDebug(true);
	$logger = new Logger('defaultLogger');
	
	if ($devConfig->getPropelLogging() == 'stderr') {
		$logger->pushHandler(new StreamHandler('php://stderr'));
	}
	Propel::getServiceContainer()->setLogger('defaultLogger', $logger);
}

unset($dbConfig);


// general config
define('KEEKO_PATH_FILES', KEEKO_PATH . DIRECTORY_SEPARATOR . $generalConfig->getPathsFiles());
