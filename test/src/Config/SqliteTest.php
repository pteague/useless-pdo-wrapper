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
use Useless\Pdo\Config\Loader as ConfigLoader;

/**
 *
 * @package    Useless\Pdo
 */
class SqliteTest
	extends AbstractConfigTest
{
	protected function getExpectedDsn()
	{
		return 'sqlite:/tmp/' . DIRECTORY_SEPARATOR . 'pdotest.sql';
	}

	protected function newConfig()
	{
		return new Sqlite( array(
			AbstractConfig::DRIVER => ConfigLoader::CONFIG_SQLITE,
			AbstractConfig::FILEPATH => '/tmp/',
			AbstractConfig::DBNAME => 'pdotest.sql',
		) );
	}

	protected function newEmptyConfig()
	{
		return new Sqlite();
	}

	public function testDsnSocketCharset()
	{
		$config = $this->newEmptyConfig();
		$config->setUnixSocket( '/tmp/pdotest.sql' );
		$config->setCharset( 'utf8' );
		$this->assertEquals(
			'sqlite:/tmp/pdotest.sql;charset=utf8'
			,$config->getDsn()
		);
	}

	/**
	 * @expectedException Useless\Pdo\Config\Exception\InvalidFilePermissions
	 */
	public function testDsnDirectoryException()
	{
		$config = $this->newEmptyConfig();
		$config->setUnixSocket( '/etc/' );
		$config->setDatabaseName( 'pdotest.sql' );
		$config->getDsn();
	}

	/**
	 * @expectedException Useless\Pdo\Config\Exception\InvalidFilePermissions
	 */
	public function testDsnFileException()
	{
		$config = $this->newEmptyConfig();
		$config->setUnixSocket( '/etc/hosts' );
		$config->getDsn();
	}

}

