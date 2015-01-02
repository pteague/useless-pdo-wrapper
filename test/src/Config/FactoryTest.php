<?php
/**
 *
 * @copyright  (c) Patrick Teague
 * @link       https://github.com/pteague/useless-pdo-wrapper/
 * @date       2014-11-13
 * @license    For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * @package    useless/pdo-wrapper
 */


/**
 * Part of useless/pdo-wrapper
 *
 * @category useless/pdo-wrapper
 */
namespace Useless\Pdo\Config;

use PHPUnit_Framework_TestCase;
use Useless\Pdo\Config\Factory as ConfigLoader;
use Useless\Pdo\Config\AbstractConfig;

/**
 *
 * @package    Useless\Pdo
 */
class FactoryTest
	extends PHPUnit_Framework_TestCase
{
	public function connectionConfig()
	{
		$provider = array(
			array(
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_MYSQL,
					AbstractConfig::HOST => '192.168.16.8',
					AbstractConfig::HOST_READ_ONLY => array(
						'192.168.16.8',
					),
					AbstractConfig::PORT => '3306',
					AbstractConfig::DBNAME => 'mydb',
					AbstractConfig::USERNAME => 'george',
					AbstractConfig::PASSWORD => 'smalldog',
				),
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_MYSQL,
					AbstractConfig::HOST => '192.168.16.8',
					AbstractConfig::DBNAME => 'mydb',
					AbstractConfig::USERNAME => 'george',
					AbstractConfig::PASSWORD => 'smalldog',
					AbstractConfig::FILEPATH => null,
					AbstractConfig::PORT => '3306',
					AbstractConfig::CHARSET => null,
					AbstractConfig::DSN => 'mysql:host=192.168.16.8;port=3306;dbname=mydb',
				),
			),
			array(
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_MYSQL,
					AbstractConfig::HOST => '192.168.16.8',
					AbstractConfig::HOST_READ_ONLY => array(
						'192.168.16.9',
					),
					AbstractConfig::PORT => '3306',
					AbstractConfig::DBNAME => 'iss',
					AbstractConfig::USERNAME => 'username',
					AbstractConfig::PASSWORD => 'password',
					AbstractConfig::USE_READ_ONLY => true,
				),
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_MYSQL,
					AbstractConfig::HOST => '192.168.16.9',
					AbstractConfig::DBNAME => 'iss',
					AbstractConfig::USERNAME => 'username',
					AbstractConfig::PASSWORD => 'password',
					AbstractConfig::FILEPATH => null,
					AbstractConfig::PORT => '3306',
					AbstractConfig::CHARSET => null,
					AbstractConfig::DSN => 'mysql:host=192.168.16.9;port=3306;dbname=iss',
				),
			),
			array(
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_SQLITE,
					AbstractConfig::FILEPATH => '/tmp/mydb.sql',
				),
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_SQLITE,
					AbstractConfig::FILEPATH => '/tmp/mydb.sql',
					AbstractConfig::DBNAME => null,
					AbstractConfig::HOST => null,
					AbstractConfig::USERNAME => null,
					AbstractConfig::PASSWORD => null,
					AbstractConfig::PORT => null,
					AbstractConfig::CHARSET => null,
					AbstractConfig::DSN => 'sqlite:/tmp/mydb.sql',
				),
			),
			array(
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_SQLITE,
					AbstractConfig::FILEPATH => '/tmp/',
					AbstractConfig::DBNAME => 'otherdb.sql',
				),
				array(
					AbstractConfig::DRIVER => ConfigLoader::CONFIG_SQLITE,
					AbstractConfig::FILEPATH => '/tmp/',
					AbstractConfig::DBNAME => 'otherdb.sql',
					AbstractConfig::HOST => null,
					AbstractConfig::USERNAME => null,
					AbstractConfig::PASSWORD => null,
					AbstractConfig::PORT => null,
					AbstractConfig::CHARSET => null,
					AbstractConfig::DSN => 'sqlite:/tmp/' . DIRECTORY_SEPARATOR . 'otherdb.sql',
				),
			),
		);
		return $provider;
	}

	/**
	 * @param array $config
	 * @param array $expected
	 * @dataProvider connectionConfig
	 */
	public function testConfigLoader( $config, $expected )
	{
		$cl = new ConfigLoader();
		$c = $cl->getConfig( $config );
		$this->assertEquals( $expected[ AbstractConfig::DRIVER ], $c->getDriver(), 'driver value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::HOST ], $c->getHost(), 'host value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::PORT ], $c->getPort(), 'port value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::DBNAME ], $c->getDatabaseName(), 'database value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::USERNAME ], $c->getUsername(), 'username value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::PASSWORD ], $c->getPassword(), 'password value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::FILEPATH ], $c->getUnixSocket(), 'unix_socket value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::CHARSET ], $c->getCharset(), 'charset value mismatch');
		$this->assertEquals( $expected[ AbstractConfig::DSN ], $c->getDsn(), 'DSN value mismatch');
	}

	/**
	 * @expectedException Useless\Pdo\Config\Exception\UnknownDriver
	 */
	public function testUnknownDriver()
	{
		$cl = new ConfigLoader();
		$c = $cl->getConfig( array(
			AbstractConfig::DRIVER => 'Non-Existent-Driver',
		) );
	}
}


