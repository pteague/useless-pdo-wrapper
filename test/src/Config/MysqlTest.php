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

use Useless\Pdo\Config\AbstractConfig;
use Useless\Pdo\Config\Factory as ConfigLoader;

/**
 *
 * @package    Useless\Pdo
 */
class MysqlTest
	extends AbstractConfigTest
{
	protected function getExpectedDsn()
	{
		return 'mysql:host=192.168.122.15;port=3306;dbname=pdotest';
	}

	protected function newConfig()
	{
		return new Mysql( array(
			AbstractConfig::DRIVER => ConfigLoader::CONFIG_MYSQL,
			AbstractConfig::HOST => '192.168.122.15',
			AbstractConfig::HOST_READ_ONLY => array(
				'192.168.122.15',
			),
			AbstractConfig::PORT => '3306',
			AbstractConfig::DBNAME => 'pdotest',
			AbstractConfig::USERNAME => 'pdotest',
			AbstractConfig::PASSWORD => 'password',
		) );
	}

	protected function newEmptyConfig()
	{
		return new Mysql();
	}

	public function testDsnSocketCharset()
	{
		$config = $this->newEmptyConfig();
		$config->setUnixSocket( '/var/run/mysqld/mysqld.sock' );
		$config->setCharset( 'utf8' );
		$this->assertEquals(
			'mysql:unix_socket=/var/run/mysqld/mysqld.sock;charset=utf8'
			,$config->getDsn()
		);
	}

}

