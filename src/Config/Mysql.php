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
use Useless\Pdo\Driver\Exception\InvalidHost;

/**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @package    Useless\Pdo\Config
 */
class Mysql
	extends AbstractConfig
{
	public function addParameter( $name, $value )
	{
		if ( static::CHARSET == $name ) {
			$this->addOption( PDO::MYSQL_ATTR_INIT_COMMAND, $value );
		}
		return parent::addParameter( $name, $value );
	}

	/**
	 * @return string
	 * @throws InvalidHost
	 */
	public function getDsn()
	{
		$dsn = $this->getDriver();
		$host = array();
		if ( ( $socket = $this->getUnixSocket() ) ) {
			$host[] = 'unix_socket=' . $socket;
		}
		elseif ( ( $tmp = $this->getHost() ) ) {
			$host[] = 'host=' . $tmp;
			if ( ( $port = $this->getPort() ) ) {
				$host[] = 'port=' . $port;
			}
		}
		if ( !$host ) {
			throw new InvalidHost();
		}
		if ( ( $dbname = $this->getDatabaseName() ) ) {
			$host[] = 'dbname=' . $dbname;
		}
		if ( ( $charset = $this->getCharset() ) ) {
			$host[] = 'charset=' . $charset;
		}
		$dsn .= ':' . implode( ';', $host );
		return $dsn;
	}

	public function init()
	{
		$this->options = array(
		);
		$this->config = array(
			'driver' => 'mysql',
		);
		$this->attributes = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
		);
	}

}

