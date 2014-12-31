<?php
/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * @copyright  (c) Patrick Teague <plteague@gmail.com>
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
use Traversable;
use Useless\Pdo\ConfigInterface as ConfigInterface;
use Useless\Pdo\PDOInterface as PDOInterface;

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    Useless\Pdo\Config
 */
abstract class AbstractConfig
	implements ConfigInterface
{
	const DSN = 'dsn';
	const DRIVER = 'driver';
	const HOST = 'host';
	const HOST_READ_ONLY = 'host_ro';
	const PORT = 'port';
	const DBNAME = 'dbname';
	const UNIX_SOCKET = 'unix_socket';
	const CHARSET = 'charset';
	const USERNAME = 'user';
	const PASSWORD = 'pass';
	const USE_READ_ONLY = 'use_readonly';
	const FILEPATH = self::UNIX_SOCKET;
	const SQLITE_MEMORY_TABLE = ':memory:';
	
	const OPTIONS = 'options';
	const ATTRIBUTES = 'attributes';

	protected $attributes = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	);

	protected $config = array();

	protected $map = array();

	protected $options = array();

	public function __construct( array $config = array(), array $map = array() )
	{
		$this->init();
		$this->setConfigMapping( $map );
		$this->setConfig( $config );
	}

	public function addAttribute( $attribute, $value )
	{
		$this->attributes[ $attribute ] = $value;
	}

	public function addHostReadOnly( $value )
	{
		if ( !isset( $this->config[ static::HOST_READ_ONLY ] ) ) {
			$this->config[ static::HOST_READ_ONLY ] = array();
		}
		$this->config[ static::HOST_READ_ONLY ][] = $value;
		return $this;
	}

	public function addOption( $option, $value )
	{
		$this->options[ $option ] = $value;
	}

	public function addParameter( $name, $value )
	{
		if ( isset( $this->map[ $name ] ) ) {
			$key = $this->map[ $name ];
		}
		else {
			$key = $name;
		}
		if ( static::ATTRIBUTES == $key ) {
			if ( is_array( $value ) || $value instanceof Traversable ) {
				foreach ( $value as $attribute => $val ) {
					$this->addAttribute( $attribute, $val );
				}
			}
		}
		elseif ( static::OPTIONS == $key ) {
			if ( is_array( $value ) || $value instanceof Traversable ) {
				foreach ( $value as $option => $val ) {
					$this->addOption( $option, $val );
				}
			}
		}
		else {
			$this->config[ $key ] = $value;
		}
		return $this;
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function getCharset()
	{
		return $this->getParameter( static::CHARSET );
	}

	public function getDatabaseName()
	{
		return $this->getParameter( static::DBNAME );
	}

	public function getDriver()
	{
		return $this->getParameter( static::DRIVER );
	}

	/**
	 * returns string
	 * @throws InvalidFilePermissions
	 * @throws InvalidHost
	 */
	abstract public function getDsn();

	public function getHost()
	{
		$rv = null;
		if ( $this->getUseReadOnly() ) {
			$rv = $this->getHostReadOnly();
		}
		if ( !$rv ) {
			$rv = $this->getParameter( static::HOST );
		}
		return $rv;
	}

	public function getHostReadOnly()
	{
		$ro = $this->getParameter( static::HOST_READ_ONLY );
		if ( is_array( $ro ) ) {
			$randomKey = array_rand( $ro, 1 );
			$ro = $ro[ $randomKey ];
		}
		return $ro;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function getPassword()
	{
		return $this->getParameter( static::PASSWORD );
	}

	public function getPort()
	{
		return $this->getParameter( static::PORT );
	}

	public function getParameter( $name )
	{
		if ( isset( $this->config[ $name ] ) ) {
			return $this->config[ $name ];
		}
	}

	public function getUnixSocket()
	{
		return $this->getParameter( static::UNIX_SOCKET );
	}

	public function getUseReadOnly()
	{
		return $this->getParameter( static::USE_READ_ONLY );
	}

	public function getUsername()
	{
		return $this->getParameter( static::USERNAME );
	}

	abstract protected function init();

	public function setConfigMapping( array $map = array() )
	{
		$this->map = $map;
		return $this;
	}

	public function setCharset( $value )
	{
		$this->addParameter( static::CHARSET, $value );
		return $this;
	}

	public function setDatabaseName( $value )
	{
		$this->addParameter( static::DBNAME, $value );
		return $this;
	}

	public function setConfig( array $config = array() )
	{
		foreach ( $config as $param => $value ) {
			$this->addParameter( $param, $value );
		}
		return $this;
	}

	public function setHost( $value )
	{
		$this->addParameter( static::HOST, $value );
		return $this;
	}

	public function setHostReadOnly( $value )
	{
		if ( is_array( $value ) ) {
			$this->addParameter( static::HOST_READ_ONLY, $value );
		}
		else {
			$this->addParameter( static::HOST_READ_ONLY, array( $value ) );
		}
		return $this;
	}

	public function setPassword( $value )
	{
		$this->addParameter( static::PASSWORD, $value );
		return $this;
	}

	public function setPort( $value )
	{
		$this->addParameter( static::PORT, $value );
		return $this;
	}

	public function setUnixSocket( $value )
	{
		$this->addParameter( static::UNIX_SOCKET, $value );
		return $this;
	}

	public function setUseReadOnly( $value )
	{
		$this->addParameter( static::USE_READ_ONLY, (bool)$value );
		return $this;
	}

	public function setUsername( $value )
	{
		$this->addParameter( static::USERNAME, $value );
		return $this;
	}

	public function __toString()
	{
		return (string)$this->getDsn();
	}

	public function unsetAttribute( $attribute )
	{
		unset( $this->attributes[ $attribute ] );
		return $this;
	}

	public function unsetConfig()
	{
		$this->init();
		return $this;
	}

	public function unsetOption( $option )
	{
		unset( $this->options[ $option ] );
		return $this;
	}

	public function unsetParameter( $name )
	{
		if ( isset( $this->map[ $name ] ) ) {
			$key = $this->map[ $name ];
		}
		else {
			$key = $name;
		}
		unset( $this->config[ $key ] );
		return $this;
	}

}


