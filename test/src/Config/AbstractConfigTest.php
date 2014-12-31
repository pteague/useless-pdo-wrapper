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

use PDO;
use Useless\Pdo\Config\AbstractConfig;

/**
 *
 * @package    Useless\Pdo
 */
abstract class AbstractConfigTest
	extends \PHPUnit_Framework_TestCase
{
	protected $config;

	public function setUp()
	{
		$this->config = $this->newConfig();
	}

	abstract protected function getExpectedDsn();

	abstract protected function newConfig();

	abstract protected function newEmptyConfig();

	public function testAttributes()
	{
		$config = $this->newEmptyConfig();
		$config->addAttribute( PDO::ATTR_CASE, PDO::CASE_LOWER );
		$attributes = $config->getAttributes();
		$this->assertTrue( array_key_exists( PDO::ATTR_CASE, $attributes ) );
		$this->assertEquals( PDO::CASE_LOWER, $attributes[ PDO::ATTR_CASE ] );
		$config->unsetAttribute( PDO::ATTR_CASE );
		$attributes = $config->getAttributes();
		$this->assertFalse( array_key_exists( PDO::ATTR_CASE, $attributes ) );
	}

	public function testCharset()
	{
		$config = $this->newEmptyConfig();
		$config->setCharset( 'utf8' );
		$this->assertEquals( 'utf8', $config->getCharset() );
	}

	public function testConfigMapping()
	{
		$config = $this->newEmptyConfig();
		$config->setConfigMapping( array(
			'ro_host' => AbstractConfig::HOST_READ_ONLY,
			'apples' => AbstractConfig::USERNAME,
		) );
		$roHost = '192.168.122.15';
		$username = 'pdotester';
		$config->addParameter( 'ro_host', array( $roHost ) );
		$config->addParameter( 'apples', $username );
		$this->assertEquals( $roHost, $config->getHostReadOnly() );
		$this->assertEquals( $username, $config->getUsername() );
		$config->unsetParameter( 'ro_host' );
		$config->unsetParameter( 'apples' );
		$this->assertNotEquals( $roHost, $config->getHostReadOnly() );
		$this->assertEmpty( $config->getHostReadOnly() );
		$this->assertNotEquals( $username, $config->getUsername() );
		$this->assertEmpty( $config->getUsername() );
		$config->setUsername( $username );
		$this->assertEquals( $username, $config->getUsername() );
		$config->unsetParameter( AbstractConfig::USERNAME );
		$this->assertNotEquals( $username, $config->getUsername() );
		$this->assertEmpty( $config->getUsername() );
	}

	public function testDatabaseName()
	{
		$config = $this->newEmptyConfig();
		$config->setDatabaseName( 'pdotest' );
		$this->assertEquals( 'pdotest', $config->getDatabaseName() );
	}

	public function testDsn()
	{
		$config = $this->newConfig();
		$this->assertEquals( $this->getExpectedDsn(), $config->getDsn() );
	}

	/**
	 * @expectedException Useless\Pdo\Config\Exception\InvalidHost
	 */
	public function testDsnUnsetException()
	{
		$config = $this->newConfig();
		$this->assertEquals( $this->getExpectedDsn(), $config->getDsn() );
		$config->unsetConfig();
		$this->assertEmpty( $config->getDsn() );
	}

	public function testHost()
	{
		$config = $this->newEmptyConfig();
		$host = '192.168.122.15';
		$hostRo = array(
			'192.168.122.16',
			'192.168.122.17',
		);
		$config->setHost( $host );
		$config->setHostReadOnly( $hostRo );
		$this->assertEquals( $host, $config->getHost() );
		$config->setUseReadOnly( true );
		$getHost = $config->getHost();
		$this->assertNotEquals( $host, $getHost );
		$this->assertTrue( in_array( $getHost, $hostRo ) );
	}

	public function testHostReadOnly()
	{
		$config = $this->newEmptyConfig();
		$host = '192.168.122.15';
		$hostRo = '192.168.122.16';
		$config->setHost( $host );
		$config->setHostReadOnly( $hostRo );
		
		$this->assertEquals( $host, $config->getHost() );
		$config->setUseReadOnly( true );
		$getHost = $config->getHost();
		$this->assertNotEquals( $host, $getHost );
		$this->assertEquals( $hostRo, $getHost );
	}

	public function testHostReadOnlyArray()
	{
		$config = $this->newEmptyConfig();
		$host = '192.168.122.15';
		$hostRo = array(
			'192.168.122.16',
			'192.168.122.17',
		);
		$config->setHost( $host );
		foreach ( $hostRo as $ip ) {
			$config->addHostReadOnly( $ip );
		}
		$this->assertEquals( $host, $config->getHost() );
		$config->setUseReadOnly( true );
		$getHost = $config->getHost();
		$this->assertNotEquals( $host, $getHost );
		$this->assertTrue( in_array( $getHost, $hostRo ) );
	}

	public function testOptions()
	{
		$config = $this->newEmptyConfig();
		$config->addOption( PDO::ATTR_CASE, PDO::CASE_LOWER );
		$attributes = $config->getOptions();
		$this->assertTrue( array_key_exists( PDO::ATTR_CASE, $attributes ) );
		$this->assertEquals( PDO::CASE_LOWER, $attributes[ PDO::ATTR_CASE ] );
		$config->unsetOption( PDO::ATTR_CASE );
		$attributes = $config->getOptions();
		$this->assertFalse( array_key_exists( PDO::ATTR_CASE, $attributes ) );
	}

	public function testParameterAttribute()
	{
		$config = $this->newEmptyConfig();
		$config->addParameter( AbstractConfig::ATTRIBUTES, array(
			PDO::ATTR_CASE => PDO::CASE_LOWER,
		) );
		$attributes = $config->getAttributes();
		$this->assertTrue( array_key_exists( PDO::ATTR_CASE, $attributes ) );
		$this->assertEquals( PDO::CASE_LOWER, $attributes[ PDO::ATTR_CASE ] );
	}

	public function testParameterOptions()
	{
		$config = $this->newEmptyConfig();
		$config->addParameter( AbstractConfig::OPTIONS, array(
			PDO::ATTR_CASE => PDO::CASE_LOWER,
		) );
		$attributes = $config->getOptions();
		$this->assertTrue( array_key_exists( PDO::ATTR_CASE, $attributes ) );
		$this->assertEquals( PDO::CASE_LOWER, $attributes[ PDO::ATTR_CASE ] );
	}

	public function testPassword()
	{
		$config = $this->newEmptyConfig();
		$config->setPassword( 'password' );
		$this->assertEquals( 'password', $config->getPassword() );
	}

	public function testPort()
	{
		$config = $this->newEmptyConfig();
		$config->setPort( '3307' );
		$this->assertEquals( '3307', $config->getPort() );
	}

	public function testUnixSocket()
	{
		$config = $this->newEmptyConfig();
		$config->setUnixSocket( '/var/run/mysqld/mysqld.sock' );
		$this->assertEquals( '/var/run/mysqld/mysqld.sock', $config->getUnixSocket() );
	}

	public function testUsername()
	{
		$config = $this->newEmptyConfig();
		$config->setUsername( 'pdotester' );
		$this->assertEquals( 'pdotester', $config->getUsername() );
	}

}

