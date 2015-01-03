Useless PDO Wrapper
===================

PHP PDO Wrapper with Config

# Configuration Factory

The _Config\Factory_ let's you dynamically instantiate a class that implements the
_ConfigInterface_ in case your current configurations are not using a standard _PDO_
DSN.

```php
<?php
use PDO;
use Useless\Pdo\Config\Factory;
use Useless\Pdo\Config\AbstractConfig;

$config = array(
	AbstractConfig::DRIVER => Factory::CONFIG_MYSQL,
	AbstractConfig::HOST => 'localhost',
	AbstractConfig::DBNAME => 'test',
	AbstractConfig::CHARSET => 'utf8',
	AbstractConfig::USERNAME => 'username',
	AbstractConfig::PASSWORD => 'password',
);

$factory = new Factory();
$config = $factory->getConfig( $config );
$pdo = new PDO( $config->getDsn(), $config->getUsername(), $config->getPassword(), $config->getOptions() );
foreach ( $config->getAttributes() as $attribute => $value ) {
	$pdo->setAttribute( $attribute, $value );
}
```

# Lazy Connection

Instantiation can be done the same as with the native _PDO_ class: a data source
name, username, password, and driver options. An additional parameter allows for
passing attributes to be set after the connection is made.

```php
<?php
use Useless\Pdo\Wrapper;

$pdo = new Wrapper(
	'mysql:localhost;dbname=test;charset=utf8',
	'username',
	'password',
	array(), # driver options as key/value pairs
	array()  # attributes to be set after connection as key/value pairs
);
```

Or you may instantiate with a class that extends the _Useless\Pdo\ConfigInterface_.

```php
<?php
use Useless\Pdo\Wrapper;

$config = $factory->getConfig( $config );

$pdo = new Wrapper( $config );
```















