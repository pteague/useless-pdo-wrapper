<?php
/**
 *
 * @copyright  (c) Patrick Teague
 * @link       https://github.com/pteague/useless-pdo-wrapper/
 * @date       2014-11-17
 * @license    For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * @package    useless/pdo-wrapper
 */


/**
 * Part of useless/pdo-wrapper
 *
 * @category useless/pdo-wrapper
 */
namespace Useless\Pdo;

use PDO;
use Useless\Pdo\ConfigInterface;

/**
 *
 * @package    Useless\Pdo
 */
class Wrapper
	extends PDO
	implements WrapperInterface
{
	/**
	 * @var PDO
	 */
	protected $pdo;

	protected $attributes = array(
		self::ATTR_ERRMODE => self::ERRMODE_EXCEPTION,
	);

	/**
	 * @var \Useless\Pdo\ConfigInterface
	 */
	protected $config;

	/**
	 * @var string
	 */
	protected $dsn;

	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @param string $dsn
	 * @param string $username
	 * @param string $password
	 * @param array $options
	 * @param array $attributes
	 */
	public function __construct(
		$dsn
		,$username = null
		,$password = null
		,array $options = array()
		,array $attributes = array()
	) {
		if ( $dsn instanceof ConfigInterface ) {
			$this->setConfig( $dsn );
		}
		else {
			$this->setDsn( $dsn );
			$this->setPassword( $password );
			$this->setUsername( $username );
			$this->options = $options;
			$this->attributes = array_replace( $this->attributes, $attributes );
		}
	}

	/**
	 * @see http://php.net/manual/en/pdo.begintransaction.php
	 * @return bool
	 */
	public function beginTransaction()
	{
		return $this->getPdo()->beginTransaction();
	}

	/**
	 * @see http://php.net/manual/en/pdo.commit.php
	 * @return bool
	 */
	public function commit()
	{
		return $this->getPdo()->commit();
	}

	/**
	 * @param string $sql
	 * @param array $params
	 * @param bool $questionMarks
	 * @return string
	 */
	static public function debugConvertSql( $sql, array $params, $questionMarks = false )
	{
		$pattern = array();
		$replace = array();
		if ( $questionMarks ) {
			$limit = 1;
		}
		else {
			$limit = -1;
		}
		foreach ( $params as $key => $value ) {
			if ( $questionMarks ) {
				$pattern[] = '@\?@';
			}
			else {
				$pattern[] = '@:' . preg_quote( $key, '@' ) . '\b@';
			}
			$replace[] = str_replace( '$', '\\$', var_export( $value, true ) );
		}

		if ( $params ) {
			return preg_replace( $pattern, $replace, $sql, $limit );
		}
		else {
			return $sql;
		}
	}

	/**
	 * @see http://php.net/manual/en/pdo.errorcode.php
	 * @return mixed
	 */
	public function errorCode()
	{
		return $this->getPdo()->errorCode();
	}

	/**
	 * @see http://php.net/manual/en/pdo.errorinfo.php
	 * @return array
	 */
	public function errorInfo()
	{
		return $this->getPdo()->errorInfo();
	}

	/**
	 * @see http://php.net/manual/en/pdo.exec.php
	 * @param string $statement
	 * @return int
	 */
	public function exec( $statement )
	{
		return $this->getPdo()->exec( $statement );
	}

	/**
	 * @see http://php.net/manual/en/pdo.getattribute.php
	 * @param int $attribute
	 * @return $this|mixed
	 */
	public function getAttribute( $attribute )
	{
		return $this->getPdo()->getAttribute( $attribute );
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function getDsn()
	{
		return $this->dsn;
	}

	public function getOptions()
	{
		return $this->options;
	}

	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @return \PDO
	 */
	public function getPdo()
	{
		if ( !$this->pdo ) {
			if ( !$this->hasStatementClass() ) {
				$this->setStatementClass();
			}
			$this->pdo = new PDO(
				(string)$this->getDsn()
				,$this->getUsername()
				,$this->getPassword()
				,$this->getOptions()
			);
			foreach ( $this->getAttributes() as $attribute => $value ) {
				$this->pdo->setAttribute( $attribute, $value );
			}
		}
		return $this->pdo;
	}

	public function getUsername()
	{
		return $this->username;
	}

	protected function hasStatementClass()
	{
		$attributes = $this->getAttributes();
		return array_key_exists( self::ATTR_STATEMENT_CLASS, $attributes );
	}

	/**
	 * @see http://php.net/manual/en/pdo.intransaction.php
	 * @return bool
	 */
	public function inTransaction()
	{
		return $this->getPdo()->inTransaction();
	}

	/**
	 * @see http://php.net/manual/en/pdo.lastinsertid.php
	 * @param string $name
	 * @return string
	 */
	public function lastInsertId( $name = null )
	{
		return $this->getPdo()->lastInsertId( $name );
	}

	/**
	 * @see http://php.net/manual/en/pdo.prepare.php
	 * @param string $statement
	 * @param array $driver_options
	 * @return \PDOStatement
	 */
	public function prepare( $statement, array $driver_options = array() )
	{
		var_dump( $statement );
		$pdo = $this->getPdo();
		return $pdo->prepare( $statement, $driver_options );
	}

	/**
	 * @see http://php.net/manual/en/pdo.query.php
	 * @param string $statement
	 * @param int $type
	 * @param mixed $classname
	 * @param array $ctorargs
	 * @return \PDOStatement
	 */
	public function query( $statement, $type = null, $classname = null, array $ctorargs = array() )
	{
		if ( $ctorargs ) {
			return $this->getPdo()->query( $statement, $type, $classname, $ctorargs );
		}
		elseif ( $classname ) {
			return $this->getPdo()->query( $statement, $type, $classname );
		}
		elseif ( $type ) {
			return $this->getPdo()->query( $statement, $type );
		}
		else {
			return $this->getPdo()->query( $statement );
		}
	}

	/**
	 * @see http://php.net/manual/en/pdo.quote.php
	 * @param string $string
	 * @param int $parameter_type
	 * @return string|void
	 */
	public function quote( $string, $parameter_type = PDO::PARAM_STR )
	{
		return $this->getPdo()->quote( $string, $parameter_type );
	}

	/**
	 * @see http://php.net/manual/en/pdo.rollback.php
	 * @return bool
	 */
	public function rollBack()
	{
		return $this->getPdo()->rollBack();
	}

	/**
	 * @see http://php.net/manual/en/pdo.setattribute.php
	 * @param mixed $attribute
	 * @param mixed $value
	 * @return bool|void
	 */
	public function setAttribute( $attribute, $value )
	{
		$this->attributes[ $attribute ] = $value;
		if ( $this->pdo ) {
			return $this->getPdo()->setAttribute( $attribute, $value );
		}
		return true;
	}

	public function setConfig( ConfigInterface $config )
	{
		$this->pdo = null;
		$this->config = $config;
		$attrs = $config->getOptions();
		foreach ( $attrs as $attribute => $value ) {
			$this->setOption( $attribute, $value );
		}
		$attrs = $config->getAttributes();
		foreach ( $attrs as $attribute => $value ) {
			$this->setAttribute( $attribute, $value );
		}
		$this->setDsn( (string)$config );
		$this->setUsername( $config->getUsername() );
		$this->setPassword( $config->getPassword() );
	}

	public function setDsn( $dsn )
	{
		$this->pdo = null;
		$this->dsn = (string)$dsn;
		return $this;
	}

	public function setOption( $attribute, $value )
	{
		$this->pdo = null;
		$this->options[ $attribute ] = $value;
		return $this;
	}

	public function setPassword( $password )
	{
		$this->pdo = null;
		$this->password = $password;
		return $this;
	}

	protected function setStatementClass()
	{
		$this->setAttribute( self::ATTR_STATEMENT_CLASS, array(
			'\Useless\Pdo\Statement',
			array( $this ),
		) );
	}

	public function setUsername( $username )
	{
		$this->pdo = null;
		$this->username = $username;
		return $this;
	}

}




