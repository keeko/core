<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('keeko', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=localhost;dbname=keeko',
  'user' => 'root',
  'password' => '',
  'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
));
$manager->setName('keeko');
$serviceContainer->setConnectionManager('keeko', $manager);
$serviceContainer->setDefaultDatasource('keeko');